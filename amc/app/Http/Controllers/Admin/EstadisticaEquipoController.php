<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstadisticaEquipoController extends Controller
{
    public function subirDesdeImagen(Request $request)
{
    try {
        $request->validate([
            'imagen' => 'required|image|max:5120',
        ]);

        if (!$request->hasFile('imagen') || !$request->file('imagen')->isValid()) {
            throw new \Exception('No se recibió un archivo válido.');
        }

        $file = $request->file('imagen');
        $filename = uniqid('ocr_') . '.' . $file->getClientOriginalExtension();
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        $dirs = [
            $tempDir = storage_path('app/ocr_temp'),
            $processedDir = storage_path('app/ocr_processed'),
            $cutsDir = storage_path('app/ocr_cuts'),
            $tsvDir = storage_path('app/ocr_cuts_tsv'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0775, true);
        }

        $fullTempPath = $tempDir . '/' . $filename;
        $file->move($tempDir, $filename);

        $processedPath = $processedDir . '/' . $filename;
        $this->procesarImagenGD($fullTempPath, $processedPath);

        $img = $this->crearImagenGD($processedPath);
        $corteHorizontal = $this->detectarCorteHorizontal($img);

        $imgSuperior = imagecreatetruecolor(imagesx($img), $corteHorizontal);
        imagecopy($imgSuperior, $img, 0, 0, 0, 0, imagesx($img), $corteHorizontal);
        $rutaSuperior = $cutsDir . '/' . $basename . '_superior.png';
        imagepng($imgSuperior, $rutaSuperior);
        imagedestroy($imgSuperior);

        $altoResto = imagesy($img) - $corteHorizontal;
        $imgResto = imagecreatetruecolor(imagesx($img), $altoResto);
        imagecopy($imgResto, $img, 0, 0, 0, $corteHorizontal, imagesx($img), $altoResto);
        imagedestroy($img);

        $rutasCortesImgs = $this->guardarCortesImagenAutomaticoDesdeImg($imgResto, $cutsDir, $basename);
        imagedestroy($imgResto);

        $rutasCortesImgs['superior'] = $rutaSuperior;

        $esperados = ['superior', 'centro', 'izquierda', 'derecha'];
        $faltantes = array_diff($esperados, array_keys($rutasCortesImgs));
        if (!empty($faltantes)) {
            throw new \Exception('Faltan imágenes de corte para: ' . implode(', ', $faltantes));
        }

        $rutasTSV = [];
        foreach ($rutasCortesImgs as $tipo => $rutaImagen) {
            \Log::info("Generando TSV para corte tipo: {$tipo}");
            $rutaTSV = $tsvDir . '/' . $basename . '_' . $tipo . '.tsv';
            $this->generarTSVconTesseract($rutaImagen, $rutaTSV);

            if (!file_exists($rutaTSV)) {
                throw new \Exception("No se generó el archivo TSV para tipo: {$tipo}");
            }

            $rutasTSV[$tipo] = $rutaTSV;
        }

        $faltanTSV = array_filter($esperados, fn($tipo) => !file_exists($rutasTSV[$tipo] ?? ''));
        if (!empty($faltanTSV)) {
            throw new \Exception('Faltan archivos TSV para: ' . implode(', ', $faltanTSV));
        }

        $datosEquipos = [
            'equipo_izquierdo' => ['nombre' => '', 'marcador' => 0, 'stats' => [], 'detalles' => ['lineas' => []]],
            'equipo_derecho' => ['nombre' => '', 'marcador' => 0, 'stats' => [], 'detalles' => ['lineas' => []]],
        ];

        foreach ($rutasTSV as $tipo => $rutaAbsoluta) {
            $contenidoTSV = file_get_contents($rutaAbsoluta);
            \Log::info("Procesando TSV para tipo: {$tipo} - tamaño contenido: " . strlen($contenidoTSV));

            $this->extraerDatosDesdeTSV($contenidoTSV, $tipo, $datosEquipos);
        }

        return response()->json([
            'success' => true,
            'datos' => $datosEquipos,
            'imagen_original_path' => 'storage/app/ocr_temp/' . $filename,
            'imagen_procesada_path' => 'storage/app/ocr_processed/' . $filename,
            'imagen_cortes' => array_map(fn($p) => 'storage/app/ocr_cuts/' . basename($p), $rutasCortesImgs),
            'tsv_cortes' => array_map(fn($p) => 'storage/app/ocr_cuts_tsv/' . basename($p), $rutasTSV),
        ]);
    } catch (\Throwable $e) {
        Log::error('Error procesando imagen OCR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json([
            'success' => false,
            'error' => 'Error procesando la imagen: ' . $e->getMessage(),
        ], 500);
    }
}



    // ------------------------------------------------------------------------
    // FUNCIONES PRIVADAS

    /**
     * Ejecuta Tesseract para generar archivo TSV a partir de una imagen.
     */
    private function generarTSVconTesseract(string $rutaImagen, string $rutaGuardarTSV): void
    {
        $rutaSinExt = str_replace('.tsv', '', $rutaGuardarTSV);
        $cmd = sprintf(
            'tesseract %s %s --oem 3 --psm 6 tsv -c tessedit_char_whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789%%().,-" 2>&1',
            escapeshellarg($rutaImagen),
            escapeshellarg($rutaSinExt)
        );
        exec($cmd, $output, $ret);
        if ($ret !== 0) {
            throw new \Exception("Error ejecutando Tesseract: " . implode("\n", $output));
        }
        if (!file_exists($rutaGuardarTSV)) {
            throw new \Exception("No se generó el archivo TSV esperado: $rutaGuardarTSV");
        }
    }

    /**
     * Procesa la imagen para mejorar OCR (grises, binarización, filtros).
     */
    private function procesarImagenGD(string $rutaOriginal, string $rutaProcesada): void
    {
        $img = $this->crearImagenGD($rutaOriginal);
        $width = imagesx($img);
        $height = imagesy($img);

        imagefilter($img, IMG_FILTER_GRAYSCALE);

        // Binarización con umbral 240
        $blanco = imagecolorallocate($img, 255, 255, 255);
        $negro = imagecolorallocate($img, 0, 0, 0);
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $gray = $rgb & 0xFF;
                imagesetpixel($img, $x, $y, ($gray > 240) ? $blanco : $negro);
            }
        }

        // Erosión leve (elimina blanco aislado)
        $imgCopy = imagecreatetruecolor($width, $height);
        imagecopy($imgCopy, $img, 0, 0, 0, 0, $width, $height);
        for ($x = 1; $x < $width - 1; $x++) {
            for ($y = 1; $y < $height - 1; $y++) {
                $pixel = imagecolorat($imgCopy, $x, $y) & 0xFF;
                if ($pixel === 255) {
                    $vecinosBlancos = 0;
                    $vecinos = [
                        imagecolorat($imgCopy, $x + 1, $y) & 0xFF,
                        imagecolorat($imgCopy, $x - 1, $y) & 0xFF,
                        imagecolorat($imgCopy, $x, $y + 1) & 0xFF,
                        imagecolorat($imgCopy, $x, $y - 1) & 0xFF,
                    ];
                    foreach ($vecinos as $v) {
                        if ($v === 255) $vecinosBlancos++;
                    }
                    if ($vecinosBlancos < 2) {
                        imagesetpixel($img, $x, $y, $negro);
                    }
                }
            }
        }
        imagedestroy($imgCopy);

        // Dilatación leve (diagonales)
        $imgCopyDil = imagecreatetruecolor($width, $height);
        imagecopy($imgCopyDil, $img, 0, 0, 0, 0, $width, $height);
        for ($x = 1; $x < $width - 1; $x++) {
            for ($y = 1; $y < $height - 1; $y++) {
                $pixel = imagecolorat($imgCopyDil, $x, $y) & 0xFF;
                if ($pixel === 0) {
                    $vecinosDiagonales = [
                        imagecolorat($imgCopyDil, $x - 1, $y - 1) & 0xFF,
                        imagecolorat($imgCopyDil, $x + 1, $y - 1) & 0xFF,
                        imagecolorat($imgCopyDil, $x - 1, $y + 1) & 0xFF,
                        imagecolorat($imgCopyDil, $x + 1, $y + 1) & 0xFF,
                    ];
                    foreach ($vecinosDiagonales as $v) {
                        if ($v === 255) {
                            imagesetpixel($img, $x, $y, $blanco);
                            break;
                        }
                    }
                }
            }
        }
        imagedestroy($imgCopyDil);

        // Contraste fuerte
        imagefilter($img, IMG_FILTER_CONTRAST, -40);

        // Suavizado con convolución tipo Gaussian blur
        $kernel = [
            [1, 2, 1],
            [2, 4, 2],
            [1, 2, 1]
        ];
        imageconvolution($img, $kernel, 16, 0);

        imagepng($img, $rutaProcesada);
        imagedestroy($img);
    }

    /**
     * Crea recurso GD desde archivo (JPEG, PNG, GIF).
     */
    private function crearImagenGD(string $ruta)
    {
        $info = getimagesize($ruta);
        $tipo = $info[2];

        return match ($tipo) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($ruta),
            IMAGETYPE_PNG => imagecreatefrompng($ruta),
            IMAGETYPE_GIF => imagecreatefromgif($ruta),
            default => throw new \Exception("Formato de imagen no soportado"),
        };
    }

    /**
     * Detecta el corte horizontal para separar parte superior y resto (basado en texto "resumen" o espacio).
     */
    private function detectarCorteHorizontal($img): int
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $limiteMaximoCorte = intval($height / 3);

        // Guardar temporal para OCR
        $tmpImgPath = sys_get_temp_dir() . '/' . uniqid('ocr_', true) . '.png';
        imagepng($img, $tmpImgPath);

        $tsvPath = $tmpImgPath . '.tsv';
        exec("tesseract " . escapeshellarg($tmpImgPath) . " " . escapeshellarg($tmpImgPath) . " --psm 6 tsv");

        $posicionYResumen = null;

        if (file_exists($tsvPath)) {
            $tsv = file_get_contents($tsvPath);
            unlink($tsvPath);
            unlink($tmpImgPath);

            $lineas = explode("\n", trim($tsv));
            $header = str_getcsv(array_shift($lineas), "\t");
            $colIndex = array_flip($header);

            foreach ($lineas as $line) {
                $cols = str_getcsv($line, "\t");
                if (count($cols) < count($header)) continue;

                $text = strtolower(trim($cols[$colIndex['text']] ?? ''));
                if (strpos($text, 'resumen') !== false) {
                    $posicionYResumen = intval($cols[$colIndex['top']] ?? 0);
                    break;
                }
            }
        } else {
            unlink($tmpImgPath);
        }

        if ($posicionYResumen !== null) {
            return max($posicionYResumen - 5, 0);
        }

        // Si no se encontró resumen, detectar espacios blancos para cortar
        $umbralBlanco = 0.8;
        $minAltoEspacio = 5;
        $maxAltoEspacio = 50;

        $espacios = [];
        $enEspacio = false;
        $inicioEspacio = 0;

        for ($y = 0; $y < $height; $y++) {
            $pixelesBlancos = 0;
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                if ($r === 255 && $g === 255 && $b === 255) {
                    $pixelesBlancos++;
                }
            }
            $porcentajeBlanco = $pixelesBlancos / $width;

            if ($porcentajeBlanco >= $umbralBlanco) {
                if (!$enEspacio) {
                    $enEspacio = true;
                    $inicioEspacio = $y;
                }
            } else {
                if ($enEspacio) {
                    $finEspacio = $y - 1;
                    $altoEspacio = $finEspacio - $inicioEspacio + 1;
                    if ($altoEspacio >= $minAltoEspacio && $altoEspacio <= $maxAltoEspacio) {
                        $espacios[] = ['inicio' => $inicioEspacio, 'fin' => $finEspacio];
                    }
                    $enEspacio = false;
                }
            }
        }
        if ($enEspacio) {
            $finEspacio = $height - 1;
            $altoEspacio = $finEspacio - $inicioEspacio + 1;
            if ($altoEspacio >= $minAltoEspacio && $altoEspacio <= $maxAltoEspacio) {
                $espacios[] = ['inicio' => $inicioEspacio, 'fin' => $finEspacio];
            }
        }

        if (count($espacios) === 0) {
            return intval($height / 5);
        }

        $lineaBusqueda = $espacios[0]['fin'] + 1;

        $ultimaLineaNumeros = null;
        $numerosConsecutivos = 0;

        for ($y = $lineaBusqueda; $y < $height && $y <= $limiteMaximoCorte; $y++) {
            $pixelesOscuros = 0;
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                if ($r < 100 && $g < 100 && $b < 100) {
                    $pixelesOscuros++;
                }
            }

            if ($pixelesOscuros > ($width * 0.1)) {
                $ultimaLineaNumeros = $y;
                $numerosConsecutivos++;
            } else {
                if ($numerosConsecutivos >= 1) {
                    return min($ultimaLineaNumeros + 1, $limiteMaximoCorte);
                }
                $numerosConsecutivos = 0;
            }
        }

        if ($ultimaLineaNumeros !== null) {
            return min($ultimaLineaNumeros + 1, $limiteMaximoCorte);
        }

        return min($lineaBusqueda, $limiteMaximoCorte);
    }

    /**
     * Corta la imagen en izquierda, centro y derecha según análisis OCR y pixeles oscuros.
     */
    private function guardarCortesImagenAutomaticoDesdeImg($img, string $dirSalida, string $basename): array
    {
        $imgWidth = imagesx($img);
        $imgHeight = imagesy($img);

        imagefilter($img, IMG_FILTER_GRAYSCALE);

        $tmpImgPath = sys_get_temp_dir() . '/' . uniqid('ocr_', true) . '.png';
        imagepng($img, $tmpImgPath);

        $tsvPath = $tmpImgPath . '.tsv';
        exec("tesseract " . escapeshellarg($tmpImgPath) . " " . escapeshellarg($tmpImgPath) . " --psm 6 tsv");

        if (!file_exists($tsvPath)) {
            unlink($tmpImgPath);
            throw new \Exception("No se pudo generar el archivo TSV con Tesseract.");
        }

        $tsv = file_get_contents($tsvPath);
        unlink($tmpImgPath);
        unlink($tsvPath);

        $lineas = explode("\n", trim($tsv));
        $header = str_getcsv(array_shift($lineas), "\t");
        $colIndex = array_flip($header);

        $posLB = null; // corte borde derecho para lado izquierdo
        $posRB = null; // corte borde izquierdo para lado derecho
        $posResumen = null; // posición "Resumen"

        foreach ($lineas as $line) {
            $cols = str_getcsv($line, "\t");
            if (count($cols) < count($header)) continue;

            $text = strtoupper(trim($cols[$colIndex['text']] ?? ''));
            if ($text === '') continue;

            $left = (int)$cols[$colIndex['left']];
            $top = (int)$cols[$colIndex['top']];
            $width = (int)$cols[$colIndex['width']];
            $height = (int)$cols[$colIndex['height']];
            $right = $left + $width;

            if (strpos($text, 'LB') !== false) {
                // Buscar borde real derecho desde derecha hacia izquierda, pixel oscuro
                $bordeDerechoReal = $left;
                for ($xPix = $right - 1; $xPix >= $left; $xPix--) {
                    $colOscura = false;
                    for ($yPix = $top; $yPix < $top + $height; $yPix++) {
                        if ($xPix >= $imgWidth || $yPix >= $imgHeight) continue;
                        $rgb = imagecolorat($img, $xPix, $yPix);
                        if (($rgb & 0xFF) < 100) {
                            $colOscura = true;
                            break;
                        }
                    }
                    if ($colOscura) {
                        $bordeDerechoReal = $xPix + 1;
                        break;
                    }
                }
                $margenNegativo = 5;
                $posLB = max($posLB ?? 0, max($left, $bordeDerechoReal - $margenNegativo));
            }

            if (strpos($text, 'RB') !== false) {
                if ($posRB === null || $left < $posRB) {
                    $posRB = $left;
                }
            }

            if (strpos($text, 'RESUMEN') !== false) {
                if ($posResumen === null || $left < $posResumen) {
                    $posResumen = $left;
                }
            }
        }

        if ($posLB === null) $posLB = intval($imgWidth * 0.3);
        if ($posRB === null) $posRB = intval($imgWidth * 0.7);
        if ($posResumen === null) $posResumen = $posLB;

        if ($posLB >= $posResumen) {
            $posLB = max(0, $posResumen - 20);
        }
        if ($posRB <= $posResumen) {
            $posRB = min($imgWidth, $posResumen + 20);
        }

        // Cortes: izquierda, centro, derecha
        $cortes = [];

        // Izquierda (desde 0 hasta posLB)
        $wIzq = $posLB;
        $imgIzq = imagecreatetruecolor($wIzq, $imgHeight);
        imagecopy($imgIzq, $img, 0, 0, 0, 0, $wIzq, $imgHeight);
        $rutaIzquierda = $dirSalida . '/' . $basename . '_izquierda.png';
        imagepng($imgIzq, $rutaIzquierda);
        imagedestroy($imgIzq);
        $cortes['izquierda'] = $rutaIzquierda;

        // Centro (de posLB a posRB)
        $wCentro = $posRB - $posLB;
        $imgCentro = imagecreatetruecolor($wCentro, $imgHeight);
        imagecopy($imgCentro, $img, 0, 0, $posLB, 0, $wCentro, $imgHeight);
        $rutaCentro = $dirSalida . '/' . $basename . '_centro.png';
        imagepng($imgCentro, $rutaCentro);
        imagedestroy($imgCentro);
        $cortes['centro'] = $rutaCentro;

        // Derecha (de posRB a ancho total)
        $wDer = $imgWidth - $posRB;
        $imgDer = imagecreatetruecolor($wDer, $imgHeight);
        imagecopy($imgDer, $img, 0, 0, $posRB, 0, $wDer, $imgHeight);
        $rutaDerecha = $dirSalida . '/' . $basename . '_derecha.png';
        imagepng($imgDer, $rutaDerecha);
        imagedestroy($imgDer);
        $cortes['derecha'] = $rutaDerecha;

        return $cortes;
    }

private function extraerDatosDesdeTSV(string $tsvContenido, string $tipoImagen, array &$datosEquipos): void
{
    $tipoImagen = strtolower(trim($tipoImagen));
    \Log::info("extraerDatosDesdeTSV con tipoImagen: '{$tipoImagen}'");

    $lineas = explode("\n", trim($tsvContenido));
    $headers = null;
    $tsvFilas = [];

    foreach ($lineas as $linea) {
        $cols = explode("\t", $linea);
        if (!$headers) {
            $headers = $cols;
            continue;
        }
        $fila = [];
        foreach ($headers as $i => $header) {
            $fila[$header] = $cols[$i] ?? null;
            if (is_numeric($fila[$header])) {
                $fila[$header] = (strpos($fila[$header], '.') !== false) ? floatval($fila[$header]) : intval($fila[$header]);
            }
        }
        $tsvFilas[] = $fila;
    }

    if (!isset($datosEquipos['equipo_izquierdo'])) {
        $datosEquipos['equipo_izquierdo'] = ['stats' => []];
    }
    if (!isset($datosEquipos['equipo_derecho'])) {
        $datosEquipos['equipo_derecho'] = ['stats' => []];
    }

    $estadisticasLado = [
        'regates_exitosos' => ['TASADEEXITOENREGATES', 'TASA DE EXITO EN REGATES'],
        'precision_tiros' => ['PRECISIONENTIROS', 'PRECISION EN TIROS'],
        'precision_pases' => ['PRECISIONDEPASES', 'PRECISION DE PASES'],
    ];

    switch ($tipoImagen) {
        case 'superior':
            \Log::info("Llamando extraerSuperior");
            $this->extraerSuperior($tsvFilas, $datosEquipos);
            break;
        case 'centro':
            \Log::info("Llamando extraerCentro");
            $this->extraerCentro($tsvFilas, $datosEquipos);
            break;
        case 'izquierda':
            \Log::info("Llamando extraerLado para equipo_izquierdo");
            $this->extraerLado($tsvFilas, $datosEquipos, 'equipo_izquierdo', $estadisticasLado);
            break;
        case 'derecha':
            \Log::info("Llamando extraerLado para equipo_derecho");
            $this->extraerLado($tsvFilas, $datosEquipos, 'equipo_derecho', $estadisticasLado);
            break;
        default:
            \Log::warning("Tipo de imagen no reconocido en extraerDatosDesdeTSV: {$tipoImagen}");
            break;
    }
}

private function extraerLado(array $tsvRows, array &$datosEquipos, string $claveEquipo, array $estadisticas): void
{
    // Inicializar con 'N/A'
    foreach ($estadisticas as $key => $_) {
        $datosEquipos[$claveEquipo]['stats'][$key] = 'N/A';
    }

    $normalizarTexto = fn($texto) => str_replace(
        [' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'],
        ['', 'a', 'e', 'i', 'o', 'u', 'n'],
        strtolower(trim($texto))
    );

    $normalizarValor = function ($valor) {
        $valor = trim($valor);

        if (str_ends_with($valor, '%')) {
            $num = rtrim($valor, '%');
            if (is_numeric($num)) {
                return $num . '%';
            }
            return 'N/A';
        }

        $valorLower = strtolower($valor);
        $equivCero = ['oo', 'o0', '0o', 'o', '0', 'óo', 'oó', 'oóo', 'o0o'];
        if (in_array($valorLower, $equivCero, true)) {
            return '0';
        }

        if (is_numeric($valor)) {
            return $valor;
        }

        return $valor;
    };

    $indexPorLinea = [];
    foreach ($tsvRows as $row) {
        $lineNum = $row['line_num'] ?? 0;
        $wordNum = $row['word_num'] ?? 0;
        $indexPorLinea[$lineNum][$wordNum] = $row;
    }

    $faltantes = [];

    for ($i = 0; $i < count($tsvRows); $i++) {
        $texto = $normalizarTexto($tsvRows[$i]['text'] ?? '');

        foreach ($estadisticas as $key => $variantes) {
            foreach ($variantes as $variante) {
                if ($texto === $normalizarTexto($variante)) {
                    $lineNum = $tsvRows[$i]['line_num'];
                    $wordNum = $tsvRows[$i]['word_num'];

                    $valIzq = $indexPorLinea[$lineNum][$wordNum - 1]['text'] ?? '';
                    $valDer = $indexPorLinea[$lineNum][$wordNum + 1]['text'] ?? '';
                    $valArriba = $indexPorLinea[$lineNum - 1][$wordNum]['text'] ?? '';
                    $valAbajo = $indexPorLinea[$lineNum + 1][$wordNum]['text'] ?? '';

                    $valorEncontrado = null;
                    foreach ([$valIzq, $valDer, $valArriba, $valAbajo] as $posibleValor) {
                        if ($posibleValor !== '' && strtolower(trim($posibleValor)) !== strtolower(trim($variante))) {
                            $valorEncontrado = $posibleValor;
                            break;
                        }
                    }

                    if ($valorEncontrado !== null) {
                        $normalizado = $normalizarValor($valorEncontrado);
                        $datosEquipos[$claveEquipo]['stats'][$key] = $normalizado;
                        \Log::info("ExtraerLado - {$claveEquipo} - {$key}: {$normalizado}");
                    } else {
                        $faltantes[] = $variante;
                    }

                    continue 3;
                }
            }
        }
    }

    if (!empty($faltantes)) {
        \Log::info("No se encontró valor para las métricas en lado {$claveEquipo}: " . implode(', ', $faltantes));
    }
}

/**
 * Extrae las estadísticas del centro y las asigna directamente en $datosEquipos.
 *
 * @param array $tsvRows Filas TSV parseadas
 * @param array &$datosEquipos Referencia al array de datos para modificar
 * @return void
 */
/**
 * Extrae las estadísticas del centro y las asigna directamente en $datosEquipos.
 *
 * @param array $tsvRows Filas TSV parseadas
 * @param array &$datosEquipos Referencia al array de datos para modificar
 * @return void
 */
private function extraerCentro(array $tsvRows, array &$datosEquipos): void
{
    $estadisticas = [
        'posesion' => ['%deposesion', '%deposesi6n', 'posesion', 'posesión'],
        'recuperacion_balon' => ['recuperaciondebaldn(seg.)', 'recuperación de balón', 'recuperacion de balon', 'recuperacion de balon (seg.)'],
        'tiros' => ['tiros'],
        'goles_esperados' => ['golesesperados', 'goles esperados'],
        'pases' => ['pases'],
        'entradas' => ['entradas'],
        'entradas_con_exito' => ['entradasconexito', 'entradas con éxito', 'entradas con exito'],
        'recuperaciones' => ['recuperaciones'],
        'atajadas' => ['atajadas'],
        'faltas_cometidas' => ['faltascometidas', 'faltas cometidas'],
        'fuera_de_lugar' => ['fuerasdelugar', 'fuera de lugar'],
        'tiros_de_esquina' => ['tirosdeesquina', 'tiros de esquina'],
        'tiros_libres' => ['tiroslibres', 'tiros libres'],
        'penales' => ['penales'],
        'tarjetas_amarillas' => ['tarjetasamarillas', 'tarjetas amarillas'],
    ];

    // Inicializar todo en "N/A"
    foreach ($estadisticas as $key => $_) {
        $datosEquipos['equipo_izquierdo']['stats'][$key] = 'N/A';
        $datosEquipos['equipo_derecho']['stats'][$key] = 'N/A';
    }

    // Normalizar texto para comparar
    $normalizarTexto = function ($texto) {
        $texto = strtolower(trim($texto));
        $texto = str_replace(
            [' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'],
            ['', 'a', 'e', 'i', 'o', 'u', 'n'],
            $texto
        );
        return $texto;
    };

    // Normalizar valor, limpiar % y corregir posibles errores OCR de ceros
    $normalizarValor = function ($valor) {
        $valor = strtolower(trim($valor));
        $valor = str_replace(',', '.', $valor); // Normalizar decimales con coma

        $equivCero = ['oo', 'o0', '0o', 'o', '0', 'óo', 'oó', 'oóo', 'o0o'];
        if (in_array($valor, $equivCero, true)) {
            return '0';
        }

        if (str_ends_with($valor, '%')) {
            $num = rtrim($valor, '%');
            if (is_numeric($num)) {
                return $num . '%';
            }
            return 'N/A';
        }

        // Si es numérico simple, devolverlo
        if (is_numeric($valor)) {
            return $valor;
        }

        return 'N/A';
    };

    // Indexar por line_num y word_num para búsqueda relativa
    $indexPorLinea = [];
    foreach ($tsvRows as $row) {
        $lineNum = $row['line_num'] ?? 0;
        $wordNum = $row['word_num'] ?? 0;
        $indexPorLinea[$lineNum][$wordNum] = $row;
    }

    // Buscar cada estadística
    foreach ($tsvRows as $row) {
        $texto = $normalizarTexto($row['text'] ?? '');
        $lineNum = $row['line_num'] ?? null;
        $wordNum = $row['word_num'] ?? null;

        foreach ($estadisticas as $key => $variantes) {
            foreach ($variantes as $variante) {
                if ($texto === $normalizarTexto($variante)) {
                    // Buscar valores alrededor del texto clave: izquierda, derecha, arriba, abajo
                    $valoresPosibles = [];

                    if ($lineNum !== null && $wordNum !== null) {
                        $valoresPosibles[] = $indexPorLinea[$lineNum][$wordNum - 1]['text'] ?? '';
                        $valoresPosibles[] = $indexPorLinea[$lineNum][$wordNum + 1]['text'] ?? '';
                        $valoresPosibles[] = $indexPorLinea[$lineNum - 1][$wordNum]['text'] ?? '';
                        $valoresPosibles[] = $indexPorLinea[$lineNum + 1][$wordNum]['text'] ?? '';

                        // También buscar dos palabras a la derecha si la primera es vacía o no válida
                        if (empty(trim($valoresPosibles[1])) && isset($indexPorLinea[$lineNum][$wordNum + 2])) {
                            $valoresPosibles[1] = $indexPorLinea[$lineNum][$wordNum + 2]['text'];
                        }
                    }

                    foreach ($valoresPosibles as $valor) {
                        $valorNorm = $normalizarValor($valor);
                        if ($valorNorm !== 'N/A' && $valorNorm !== '' && strtolower($valorNorm) !== strtolower($variante)) {
                            $datosEquipos['equipo_izquierdo']['stats'][$key] = $valorNorm;
                            $datosEquipos['equipo_derecho']['stats'][$key] = $valorNorm;
                            break 3; // Salir completamente y pasar al siguiente
                        }
                    }
                }
            }
        }
    }
}




/**
 * Extrae nombres de equipos y marcador del corte superior.
 *
 * @param array $tsvRows TSV parseado en filas
 * @param array &$datosEquipos Referencia para asignar los datos
 * @return void
 */
private function extraerSuperior(array $tsvRows, array &$datosEquipos): void
{
    // Inicializar
    $datosEquipos['equipo_izquierdo']['nombre'] = 'N/A';
    $datosEquipos['equipo_derecho']['nombre'] = 'N/A';
    $datosEquipos['marcador'] = 'N/A';

    // 1) Buscar nombres de equipos: usualmente en primeras líneas (line_num bajos)
    // Tomaremos palabras con más de 2 letras para evitar basura

    $nombresDetectados = [];
    foreach ($tsvRows as $fila) {
        if ($fila['level'] == 5) {
            $texto = trim($fila['text']);
            if (strlen($texto) > 2 && preg_match('/^[\w\s\.\-]+$/u', $texto)) {
                $nombresDetectados[$fila['left']] = $texto; // usamos 'left' para ordenarlos visualmente
            }
        }
    }

    // Ordenar por posición horizontal (left)
    ksort($nombresDetectados);

    $nombres = array_values($nombresDetectados);

    // Asignar nombres a los equipos (los 2 primeros textos horizontales encontrados)
    if (count($nombres) >= 2) {
        $datosEquipos['equipo_izquierdo']['nombre'] = $nombres[0];
        $datosEquipos['equipo_derecho']['nombre'] = $nombres[1];
    }

    // 2) Buscar marcador en el texto: suele estar en formato 0-0, 2:1, 3 - 2, etc.
    // Lo buscaremos en las líneas, usualmente en la misma línea o cerca

    $marcadorRegex = '/\b(\d+)\s*[-:]\s*(\d+)\b/';

    foreach ($tsvRows as $fila) {
        if ($fila['level'] == 5) {
            if (preg_match($marcadorRegex, $fila['text'], $matches)) {
                $datosEquipos['marcador'] = $matches[0];
                break;
            }
        }
    }
}




}
