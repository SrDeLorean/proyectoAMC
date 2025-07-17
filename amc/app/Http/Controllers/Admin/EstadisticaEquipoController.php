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

        // Crear carpetas necesarias
        $tempDir = storage_path('app/ocr_temp');
        $processedDir = storage_path('app/ocr_processed');
        $cutsDir = storage_path('app/ocr_cuts');
        $tsvDir = storage_path('app/ocr_cuts_tsv');
        foreach ([$tempDir, $processedDir, $cutsDir, $tsvDir] as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0775, true);
        }

        // Guardar imagen original
        $fullTempPath = $tempDir . '/' . $filename;
        $file->move($tempDir, $filename);

        // Procesar imagen a blanco/negro
        $processedPath = $processedDir . '/' . $filename;
        $this->procesarImagenGD($fullTempPath, $processedPath);

        // Cargar imagen para cortes
        $img = $this->crearImagenGD($processedPath);
        $corteHorizontal = $this->detectarCorteHorizontal($img);

        // Cortar superior
        $imgSuperior = imagecreatetruecolor(imagesx($img), $corteHorizontal);
        imagecopy($imgSuperior, $img, 0, 0, 0, 0, imagesx($img), $corteHorizontal);
        $rutaSuperior = $cutsDir . '/' . $basename . '_superior.png';
        imagepng($imgSuperior, $rutaSuperior);
        imagedestroy($imgSuperior);

        // Cortar inferior
        $altoResto = imagesy($img) - $corteHorizontal;
        $imgResto = imagecreatetruecolor(imagesx($img), $altoResto);
        imagecopy($imgResto, $img, 0, 0, 0, $corteHorizontal, imagesx($img), $altoResto);
        imagedestroy($img);

        $rutasCortesImgs = $this->guardarCortesImagenAutomaticoDesdeImg($imgResto, $cutsDir, $basename);
        imagedestroy($imgResto);

        // Agregar ruta de imagen superior
        $rutasCortesImgs['superior'] = $rutaSuperior;

        // Generar TSV
        $rutasTSV = [];
        foreach ($rutasCortesImgs as $tipo => $rutaImagen) {
            $rutaTSV = $tsvDir . '/' . $basename . '_' . $tipo . '.tsv';
            $this->generarTSVconTesseract($rutaImagen, $rutaTSV);
            $rutasTSV[$tipo] = $rutaTSV;
        }

        // Inicializar estructura de datos para equipos
        $datosEquipos = [
            'equipo_izquierdo' => ['nombre' => '', 'stats' => [], 'detalles' => ['lineas' => []]],
            'equipo_derecho' => ['nombre' => '', 'stats' => [], 'detalles' => ['lineas' => []]],
        ];

        // Extraer datos con la función mejorada (que recibe &$datosEquipos para modificarlo)
        foreach ($rutasTSV as $tipo => $rutaAbsoluta) {
            if (!file_exists($rutaAbsoluta)) continue;

            $contenidoTSV = file_get_contents($rutaAbsoluta);
            // Esta función ahora debe recibir $datosEquipos por referencia
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
        Log::error('Error procesando imagen OCR: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'success' => false,
            'error' => 'Error procesando la imagen: ' . $e->getMessage(),
        ], 500);
    }
}






/**
 * Ejecuta tesseract sobre una imagen y guarda el TSV resultante
 */
private function generarTSVconTesseract(string $rutaImagen, string $rutaGuardarTSV): void
{
    // Comando Tesseract para generar TSV con PSM 6 (puedes ajustar si quieres)
    $cmd = sprintf(
        'tesseract %s %s --psm 6 tsv 2>&1',
        escapeshellarg($rutaImagen),
        escapeshellarg(str_replace('.tsv', '', $rutaGuardarTSV)) // tesseract no pide extensión en output
    );

    exec($cmd, $output, $returnVar);

    if ($returnVar !== 0) {
        throw new \Exception("Error ejecutando Tesseract: " . implode("\n", $output));
    }

    if (!file_exists($rutaGuardarTSV)) {
        throw new \Exception("No se generó el archivo TSV esperado: $rutaGuardarTSV");
    }
}


    private function procesarImagenGD(string $rutaOriginal, string $rutaProcesada): void
    {
        $img = $this->crearImagenGD($rutaOriginal);

        $width = imagesx($img);
        $height = imagesy($img);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // Si NO es blanco puro, pintar negro
                if (!($r === 255 && $g === 255 && $b === 255)) {
                    $negro = imagecolorallocate($img, 0, 0, 0);
                    imagesetpixel($img, $x, $y, $negro);
                }
            }
        }

        imagepng($img, $rutaProcesada);
        imagedestroy($img);
    }

    private function crearImagenGD(string $ruta)
    {
        $info = getimagesize($ruta);
        $tipo = $info[2];

        switch ($tipo) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($ruta);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($ruta);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($ruta);
            default:
                throw new \Exception("Formato de imagen no soportado");
        }
    }

    /**
     * Detecta corte horizontal para separar cabecera usando % pixeles blancos por fila.
     * Retorna la coordenada Y para el corte (altura superior).
     */
    private function detectarCorteHorizontal($img): int
    {
        $width = imagesx($img);
        $height = imagesy($img);

        $umbralBlanco = 0.8; // 80% blanco para considerar espacio

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
                    // Considerar espacio válido si es entre 5 y 50 px
                    if ($altoEspacio >= 5 && $altoEspacio <= 50) {
                        $espacios[] = ['inicio' => $inicioEspacio, 'fin' => $finEspacio];
                    }
                    $enEspacio = false;
                }
            }
        }
        if ($enEspacio) {
            $finEspacio = $height - 1;
            $altoEspacio = $finEspacio - $inicioEspacio + 1;
            if ($altoEspacio >= 5 && $altoEspacio <= 50) {
                $espacios[] = ['inicio' => $inicioEspacio, 'fin' => $finEspacio];
            }
        }

        if (count($espacios) === 0) {
            // No se detecta espacio, cortar a 1/5 de altura (por defecto)
            return intval($height / 5);
        }

        // Tomar el primer espacio para corte
        $primerEspacio = $espacios[0];

        // Devolver línea inmediatamente después del espacio para cortar abajo
        return $primerEspacio['fin'] + 1;
    }


private function guardarCortesImagenAutomaticoDesdeImg($img, string $dirSalida, string $basename): array
{
    $imgWidth = imagesx($img);
    $imgHeight = imagesy($img);

    // Convertir a escala de grises si no lo está
    imagefilter($img, IMG_FILTER_GRAYSCALE);

    // 1. Analizar verticalmente el contenido de la imagen para detectar columnas significativas (transiciones)
    $histogramaX = array_fill(0, $imgWidth, 0);
    for ($x = 0; $x < $imgWidth; $x++) {
        $suma = 0;
        for ($y = 0; $y < $imgHeight; $y++) {
            $rgb = imagecolorat($img, $x, $y);
            $gray = $rgb & 0xFF; // valor entre 0 (negro) y 255 (blanco)
            if ($gray > 200) $suma++; // píxel blanco
        }
        $histogramaX[$x] = $suma;
    }

    // 2. Detectar columnas "claras" separadas por bloques oscuros = posibles límites
    $limites = [];
    $umbral = (int)($imgHeight * 0.8); // columna "blanca" si al menos 80% son blancos
    $enBlanco = false;
    for ($x = 0; $x < $imgWidth; $x++) {
        if (!$enBlanco && $histogramaX[$x] >= $umbral) {
            $inicio = $x;
            $enBlanco = true;
        } elseif ($enBlanco && $histogramaX[$x] < $umbral) {
            $fin = $x;
            if (($fin - $inicio) > 30) { // mínimo 30px de bloque blanco
                $limites[] = [$inicio, $fin];
            }
            $enBlanco = false;
        }
    }

    // 3. Aplicar OCR como confirmación del bloque central
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

    $bloquesOCR = [];
    foreach ($lineas as $line) {
        $cols = str_getcsv($line, "\t");
        if (count($cols) < count($header)) continue;

        if (intval($cols[$colIndex['level']]) !== 5) continue;

        $text = trim($cols[$colIndex['text']]);
        if ($text === '') continue;

        $line_num = intval($cols[$colIndex['line_num']]);
        $left = intval($cols[$colIndex['left']]);
        $width = intval($cols[$colIndex['width']]);

        $bloquesOCR[$line_num][] = [
            'text' => $text,
            'left' => $left,
            'right' => $left + $width,
        ];
    }

    // Buscar patrón número – texto – número en cada línea OCR
    $corteCentroX1 = null;
    $corteCentroX2 = null;

    foreach ($bloquesOCR as $palabras) {
        // Buscar índice del primer número
        $primerNumIdx = null;
        for ($i = 0; $i < count($palabras); $i++) {
            $t = preg_replace('/[^\d]/', '', $palabras[$i]['text']);
            if (is_numeric($t) && $t !== '') {
                $primerNumIdx = $i;
                break;
            }
        }
        if ($primerNumIdx === null) continue;

        // Buscar índice del último número, debe estar después del primero
        $ultimoNumIdx = null;
        for ($j = count($palabras) - 1; $j > $primerNumIdx; $j--) {
            $t = preg_replace('/[^\d]/', '', $palabras[$j]['text']);
            if (is_numeric($t) && $t !== '') {
                $ultimoNumIdx = $j;
                break;
            }
        }
        if ($ultimoNumIdx === null) continue;

        // Asegurarse que haya texto entre los dos números
        if ($ultimoNumIdx - $primerNumIdx < 2) continue;

        // Cortar por fuera de los números, no justo en el límite
        $corteCentroX1 = $palabras[$primerNumIdx]['left'];  // izquierda del primer número
        $corteCentroX2 = $palabras[$ultimoNumIdx]['right']; // derecha del último número
        break;
    }

    if (!$corteCentroX1 || !$corteCentroX2 || $corteCentroX2 <= $corteCentroX1) {
        throw new \Exception("No se detectó un bloque central con patrón número – texto – número.");
    }

    // 4. Cortar las tres partes de la imagen
    $anchoCentro = $corteCentroX2 - $corteCentroX1;
    $anchoIzq = $corteCentroX1;
    $anchoDer = $imgWidth - $corteCentroX2;

    $rutas = [];

    if ($anchoIzq > 0) {
        $imgIzq = imagecreatetruecolor($anchoIzq, $imgHeight);
        imagecopy($imgIzq, $img, 0, 0, 0, 0, $anchoIzq, $imgHeight);
        $rutaIzq = $dirSalida . DIRECTORY_SEPARATOR . $basename . '_izq.png';
        imagepng($imgIzq, $rutaIzq);
        imagedestroy($imgIzq);
        $rutas['izquierda'] = $rutaIzq;
    } else {
        $rutas['izquierda'] = null;
    }

    $imgCen = imagecreatetruecolor($anchoCentro, $imgHeight);
    imagecopy($imgCen, $img, 0, 0, $corteCentroX1, 0, $anchoCentro, $imgHeight);
    $rutaCen = $dirSalida . DIRECTORY_SEPARATOR . $basename . '_cen.png';
    imagepng($imgCen, $rutaCen);
    imagedestroy($imgCen);
    $rutas['centro'] = $rutaCen;

    if ($anchoDer > 0) {
        $imgDer = imagecreatetruecolor($anchoDer, $imgHeight);
        imagecopy($imgDer, $img, 0, 0, $corteCentroX2, 0, $anchoDer, $imgHeight);
        $rutaDer = $dirSalida . DIRECTORY_SEPARATOR . $basename . '_der.png';
        imagepng($imgDer, $rutaDer);
        imagedestroy($imgDer);
        $rutas['derecha'] = $rutaDer;
    } else {
        $rutas['derecha'] = null;
    }

    return $rutas;
}


private function extraerDatosDesdeTSV(string $tsvContent, string $tipo, array &$datosEquipos): void
{
    $lineas = explode("\n", trim($tsvContent));
    if (count($lineas) === 0) {
        return;
    }

    $header = str_getcsv(array_shift($lineas), "\t");
    $colIndex = array_flip($header);

    $palabras = [];

    foreach ($lineas as $linea) {
        $campos = str_getcsv($linea, "\t");
        if (count($campos) < 12 || !is_numeric($campos[$colIndex['conf']])) continue;

        $palabras[] = [
            'text' => $campos[$colIndex['text']],
            'left' => (int) $campos[$colIndex['left']],
            'top' => (int) $campos[$colIndex['top']],
            'conf' => (int) $campos[$colIndex['conf']],
            'line_num' => (int) $campos[$colIndex['line_num']],
        ];
    }

    switch ($tipo) {
        case 'superior':
            $this->extraerSuperior($palabras, $datosEquipos);
            break;
        case 'izquierda':
            $this->extraerEstadisticasLado($palabras, $datosEquipos['equipo_izquierdo']);
            break;
        case 'derecha':
            $this->extraerEstadisticasLado($palabras, $datosEquipos['equipo_derecho']);
            break;
        case 'centro':
            $this->extraerCentro($palabras, $datosEquipos['equipo_izquierdo'], $datosEquipos['equipo_derecho']);
            break;
    }
}


private function extraerSuperior(array $palabras, array &$datosEquipos): void
{
    usort($palabras, fn($a, $b) => $a['top'] <=> $b['top']);
    $primeras = array_filter($palabras, fn($p) => $p['top'] < 120 && strlen($p['text']) > 2);

    $maxLeft = max(array_column($primeras, 'left'));
    $umbral = $maxLeft / 2;

    $izquierda = [];
    $derecha = [];

    foreach ($primeras as $p) {
        if ($p['left'] < $umbral) {
            $izquierda[] = $p['text'];
        } else {
            $derecha[] = $p['text'];
        }
    }

    $datosEquipos['equipo_izquierdo']['nombre'] = trim(implode(' ', $izquierda));
    $datosEquipos['equipo_derecho']['nombre'] = trim(implode(' ', $derecha));
}


private function extraerEstadisticasLado(array $palabras, array &$equipo): void
{
    $lineas = [];
    foreach ($palabras as $p) {
        $lineas[$p['line_num']][] = $p['text'];
    }

    $equipo['tasa_exito_regates'] = 'N/A';
    $equipo['precision_tiros'] = 'N/A';
    $equipo['precision_pases'] = 'N/A';

    // Frases posibles con OCR errores comunes
    $frasesEsperadas = [
        'tasa_exito_regates' => ['tasa de exito en regate', 'tasa de éxito en regates', 'tasa exito regate'],
        'precision_tiros' => ['precision en tiros', 'precisión en tiros', 'precision tiros', 'precision de tiros'],
        'precision_pases' => ['precision de pases', 'precisión de pases', 'precision pases'],
    ];

    // Recorremos bloques consecutivos para intentar detectar valores
    $bloques = array_values($lineas);
    for ($i = 0; $i < count($bloques); $i++) {
        $textoActual = strtolower(implode(' ', $bloques[$i]));

        // Normalización
        $frase = preg_replace('/[^a-z0-9%áéíóúñ\s]/iu', '', $textoActual);
        $frase = str_replace('  ', ' ', trim($frase));

        // Si contiene un porcentaje, buscamos a qué estadística pertenece
        if (preg_match('/(\d{1,3})\s*%/', $frase, $matches)) {
            $valor = $matches[1] . '%';

            // Unimos varias líneas por si el texto está separado
            $contexto = $frase;
            if (isset($bloques[$i + 1])) {
                $contexto .= ' ' . strtolower(implode(' ', $bloques[$i + 1]));
            }
            if (isset($bloques[$i + 2])) {
                $contexto .= ' ' . strtolower(implode(' ', $bloques[$i + 2]));
            }

            $contexto = preg_replace('/[^a-z0-9%áéíóúñ\s]/iu', '', $contexto);
            $contexto = str_replace('  ', ' ', trim($contexto));

            foreach ($frasesEsperadas as $claveFinal => $variantes) {
                foreach ($variantes as $variante) {
                    if (strpos($contexto, $variante) !== false) {
                        $equipo[$claveFinal] = $valor;
                        break 2; // rompe ambos bucles
                    }
                }
            }
        }
    }
}


private function extraerCentro(array $palabras, array &$equipoIzq, array &$equipoDer): void
{
    $mapaClaves = [
        'posesion' => ['posesion'],
        'recuperacion_balon' => ['recuperacion de balon', 'recuperacion balon', 'recuperacion'],
        'tiros' => ['tiros'],
        'goles_esperados' => ['goles esperados', 'gol esperado', 'goles esperado'],
        'pases' => ['pases'],
        'entradas' => ['entradas'],
        'entradas_con_exito' => ['entradas con exito', 'entradas con éxito'],
        'recuperaciones' => ['recuperaciones'],
        'atajadas' => ['atajadas'],
        'faltas' => ['faltas', 'faltas cometidas', 'falta'],
        'fueras_de_lugar' => ['fueras de lugar'],
        'tiros_de_esquina' => ['tiros de esquina'],
        'tiros_libres' => ['tiros libres'],
        'penales' => ['penales'],
        'tarjetas_amarillas' => ['tarjetas amarillas', 'tarjeta amarilla'],
    ];

    // Agrupamos palabras por línea y ordenamos
    $lineas = [];
    foreach ($palabras as $p) {
        $lineas[$p['line_num']][] = $p;
    }
    ksort($lineas);

    // Inicializamos resultados con "N/A"
    $resultadosIzq = array_fill_keys(array_keys($mapaClaves), 'N/A');
    $resultadosDer = array_fill_keys(array_keys($mapaClaves), 'N/A');

    // Función para buscar valor numérico o N/A en las palabras de una línea,
    // a la izquierda (izq) o derecha (der) de una posición (left referencia)
    $buscarValorEnLinea = function(array $palabrasLinea, float $posLeft, string $lado) {
        // Filtrar solo palabras que tienen texto numérico o N/A
        $candidatos = [];
        foreach ($palabrasLinea as $palabra) {
            if (preg_match('/^\d+([.,]\d+)?%?$/', $palabra['text']) || strtoupper($palabra['text']) === 'N/A') {
                $candidatos[] = $palabra;
            }
        }
        if (empty($candidatos)) return 'N/A';

        if ($lado === 'izq') {
            // Elegir el candidato con posición left más cercana y menor a posLeft
            $filtrados = array_filter($candidatos, fn($p) => $p['left'] < $posLeft);
            if ($filtrados) {
                usort($filtrados, fn($a, $b) => ($posLeft - $a['left']) <=> ($posLeft - $b['left']));
                return strtoupper(str_replace(',', '.', $filtrados[0]['text']));
            }
            // Si no hay a la izquierda, tomar el más cercano a la derecha
            usort($candidatos, fn($a, $b) => abs($a['left'] - $posLeft) <=> abs($b['left'] - $posLeft));
            return strtoupper(str_replace(',', '.', $candidatos[0]['text']));
        } else {
            // lado derecho: buscar candidato con left mayor a posLeft
            $filtrados = array_filter($candidatos, fn($p) => $p['left'] > $posLeft);
            if ($filtrados) {
                usort($filtrados, fn($a, $b) => ($a['left'] - $posLeft) <=> ($b['left'] - $posLeft));
                return strtoupper(str_replace(',', '.', $filtrados[0]['text']));
            }
            // fallback candidato más cercano
            usort($candidatos, fn($a, $b) => abs($a['left'] - $posLeft) <=> abs($b['left'] - $posLeft));
            return strtoupper(str_replace(',', '.', $candidatos[0]['text']));
        }
    };

    // Para cada clave en orden
    foreach ($mapaClaves as $clave => $variantes) {
        $encontradoIzq = false;
        $encontradoDer = false;

        // Buscar en todas las líneas
        foreach ($lineas as $lineNum => $palabrasLinea) {
            // Ordenar palabras por left
            usort($palabrasLinea, fn($a, $b) => $a['left'] <=> $b['left']);

            // Texto línea para búsqueda
            $textoLinea = strtolower(trim(implode(' ', array_column($palabrasLinea, 'text'))));

            foreach ($variantes as $variante) {
                if (strpos($textoLinea, $variante) !== false) {
                    // Encontramos la línea con la clave

                    // Buscar posición horizontal de la palabra clave (primera palabra de la variante)
                    $varPalabras = explode(' ', $variante);
                    $posClave = null;
                    foreach ($palabrasLinea as $palabra) {
                        if (strtolower($palabra['text']) === strtolower($varPalabras[0])) {
                            $posClave = $palabra['left'];
                            break;
                        }
                    }
                    if ($posClave === null) {
                        // fallback: usar palabra central
                        $posClave = $palabrasLinea[intval(count($palabrasLinea)/2)]['left'];
                    }

                    // Buscar valor para equipo izquierdo (a la izquierda)
                    if (!$encontradoIzq) {
                        $valIzq = $buscarValorEnLinea($palabrasLinea, $posClave, 'izq');
                        $resultadosIzq[$clave] = $valIzq;
                        $encontradoIzq = true;
                    }

                    // Buscar valor para equipo derecho (a la derecha)
                    if (!$encontradoDer) {
                        $valDer = $buscarValorEnLinea($palabrasLinea, $posClave, 'der');
                        $resultadosDer[$clave] = $valDer;
                        $encontradoDer = true;
                    }

                    // Si ambos encontrados, siguiente clave
                    if ($encontradoIzq && $encontradoDer) {
                        break 3;
                    }
                }
            }
        }
    }

    // Guardar resultados
    $equipoIzq['estadisticas'] = $resultadosIzq;
    $equipoDer['estadisticas'] = $resultadosDer;
}





}
