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

            if (!$request->hasFile('imagen')) {
                throw new \Exception('No se recibió archivo "imagen".');
            }

            $file = $request->file('imagen');

            if (!$file->isValid()) {
                throw new \Exception('Archivo subido no es válido.');
            }

            // 1) Guardar imagen original
            $filename = uniqid('ocr_') . '.' . $file->getClientOriginalExtension();
            $tempDir = storage_path('app/ocr_temp');
            if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);
            $fullTempPath = $tempDir . DIRECTORY_SEPARATOR . $filename;
            $file->move($tempDir, $filename);

            // 2) Procesar imagen (convertir lo no blanco en negro)
            $processedDir = storage_path('app/ocr_processed');
            if (!is_dir($processedDir)) mkdir($processedDir, 0775, true);
            $processedPath = $processedDir . DIRECTORY_SEPARATOR . $filename;
            $this->procesarImagenGD($fullTempPath, $processedPath);

            // 3) Ejecutar OCR obteniendo TSV correctamente
            $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($processedPath);
            $ocr->lang('spa')
                ->psm(4)
                ->dpi(300)
                ->format('tsv'); // <- CORRECCIÓN AQUÍ

            $tsvText = $ocr->run();

            // 4) Guardar TSV en archivo
            $textDir = storage_path('app/ocr_text');
            if (!is_dir($textDir)) mkdir($textDir, 0775, true);
            $textoFilename = pathinfo($filename, PATHINFO_FILENAME) . '.tsv';
            file_put_contents($textDir . DIRECTORY_SEPARATOR . $textoFilename, $tsvText);

            // 5) Aquí puedes extraer datos del TSV si quieres (no del texto plano)
            $datosEquipos = $this->extraerDatosDesdeTSV($tsvText);

            // 6) Guardar JSON con datos extraídos
            $jsonDir = storage_path('app/ocr_json');
            if (!is_dir($jsonDir)) mkdir($jsonDir, 0775, true);
            $jsonFilename = pathinfo($filename, PATHINFO_FILENAME) . '.json';
            file_put_contents($jsonDir . DIRECTORY_SEPARATOR . $jsonFilename, json_encode($datosEquipos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // 7) Limpiar texto plano para mostrar
            $textoLimpio = preg_replace('/[ \t]+/', ' ', strip_tags($tsvText));
            $textoLimpio = preg_replace('/[\r\n]+/', "\n", $textoLimpio);
            $textoLimpio = trim($textoLimpio);

            return response()->json([
                'success' => true,
                'texto_completo' => $textoLimpio,
                'datos_equipo_izquierdo' => $datosEquipos['equipo_izquierdo'] ?? null,
                'datos_equipo_derecho' => $datosEquipos['equipo_derecho'] ?? null,
                'imagen_original_path' => 'storage/app/ocr_temp/' . $filename,
                'imagen_procesada_path' => 'storage/app/ocr_processed/' . $filename,
                'archivo_texto_path' => 'storage/app/ocr_text/' . $textoFilename,
                'archivo_json_path' => 'storage/app/ocr_json/' . $jsonFilename,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error procesando imagen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error procesando la imagen: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    private function procesarImagenGD(string $rutaOriginal, string $rutaProcesada): void
    {
        $info = getimagesize($rutaOriginal);
        $tipo = $info[2];

        switch ($tipo) {
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($rutaOriginal);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($rutaOriginal);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($rutaOriginal);
                break;
            default:
                throw new \Exception("Formato de imagen no soportado");
        }

        $width = imagesx($img);
        $height = imagesy($img);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if (!($r === 255 && $g === 255 && $b === 255)) {
                    $negro = imagecolorallocate($img, 0, 0, 0);
                    imagesetpixel($img, $x, $y, $negro);
                }
            }
        }

        imagepng($img, $rutaProcesada);
        imagedestroy($img);
    }

private function extraerDatosDesdeTSV(string $tsv): array
{
    $lineas = explode("\n", trim($tsv));
    $header = str_getcsv(array_shift($lineas), "\t");
    $colIndex = array_flip($header);

    $lineasPalabras = [];
    foreach ($lineas as $line) {
        $cols = str_getcsv($line, "\t");
        if (count($cols) < count($header)) continue;

        $level = $cols[$colIndex['level']];
        $line_num = $cols[$colIndex['line_num']];
        $text = $cols[$colIndex['text']];
        $left = intval($cols[$colIndex['left']]);

        if ($level == 5 && trim($text) !== '') {
            $lineasPalabras[$line_num][] = ['text' => $text, 'left' => $left];
        }
    }

    $todosLefts = [];
    foreach ($lineasPalabras as $ln => $words) {
        foreach ($words as $w) {
            $todosLefts[] = $w['left'];
        }
    }
    $corte = (max($todosLefts) + min($todosLefts)) / 2;

    $etiquetas = [
        'POSESION',
        'TASA DE EXITO EN REGATES',
        'PRECISION EN TIROS',
        'PRECISION DE PASES',
        'RECUPERACION DE BALON',
        'TIROS',
        'GOLES ESPERADOS',
        'PASES',
        'ENTRADAS',
        'ENTRADAS CON EXITO',
        'RECUPERACIONES',
        'ATAJADAS',
        'FALTAS COMETIDAS',
        'FUERAS DE LUGAR',
        'TIROS DE ESQUINA',
        'TIROS LIBRES',
        'PENALES',
        'TARJETAS AMARILLAS',
    ];

    $normalizar = fn($str) => strtoupper(str_replace(['Á','É','Í','Ó','Ú','Ü','Ñ'], ['A','E','I','O','U','U','N'], $str));

    $datosIzq = [];
    $datosDer = [];
    if (isset($lineasPalabras[1])) {
        $izqTexts = [];
        $derTexts = [];
        foreach ($lineasPalabras[1] as $w) {
            if ($w['left'] < $corte) $izqTexts[] = $w['text'];
            else $derTexts[] = $w['text'];
        }
        $izqStr = implode(' ', $izqTexts);
        $derStr = implode(' ', $derTexts);

        preg_match_all('/\d+/', $izqStr, $golesIzq);
        preg_match_all('/\d+/', $derStr, $golesDer);

        $datosIzq['nombre'] = trim(preg_replace('/\d+/', '', $izqStr));
        $datosDer['nombre'] = trim(preg_replace('/\d+/', '', $derStr));

        $datosIzq['goles'] = isset($golesIzq[0][0]) ? intval($golesIzq[0][0]) : null;
        $datosDer['goles'] = isset($golesDer[0][0]) ? intval($golesDer[0][0]) : null;
    }

    foreach ($lineasPalabras as $ln => $words) {
        $textLine = implode(' ', array_column($words, 'text'));
        $textNorm = $normalizar($textLine);

        foreach ($etiquetas as $etq) {
            $etqNorm = $normalizar($etq);

            if (strpos($textNorm, $etqNorm) !== false) {
                $valIzq = null;
                $valDer = null;
                foreach ($words as $w) {
                    $t = $w['text'];
                    if (preg_match('/^[\d.,]+%?$/', $t)) {
                        if ($w['left'] < $corte) $valIzq = $t;
                        else $valDer = $t;
                    }
                }
                $key = strtolower(str_replace(' ', '_', $etqNorm));
                if ($valIzq !== null) $datosIzq[$key] = $valIzq;
                if ($valDer !== null) $datosDer[$key] = $valDer;
            }
        }
    }

    return [
        'equipo_izquierdo' => $datosIzq,
        'equipo_derecho' => $datosDer,
    ];
}

}
