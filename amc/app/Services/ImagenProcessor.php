<?php

namespace App\Services;

class ImagenProcessor
{
    /**
     * Ejecuta tesseract sobre una imagen y guarda el TSV resultante
     */
    public function generarTSVconTesseract(string $rutaImagen, string $rutaGuardarTSV): void
    {
        $cmd = sprintf(
            'tesseract %s %s --psm 6 tsv 2>&1',
            escapeshellarg($rutaImagen),
            escapeshellarg(str_replace('.tsv', '', $rutaGuardarTSV))
        );

        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error ejecutando Tesseract: " . implode("\n", $output));
        }

        if (!file_exists($rutaGuardarTSV)) {
            throw new \Exception("No se generó el archivo TSV esperado: $rutaGuardarTSV");
        }
    }

    public function procesarImagenGD(string $rutaOriginal, string $rutaProcesada): void
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

    public function crearImagenGD(string $ruta)
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
    public function detectarCorteHorizontal($img): int
    {
        $width = imagesx($img);
        $height = imagesy($img);

        $umbralBlanco = 0.8;

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
            return intval($height / 5);
        }

        $primerEspacio = $espacios[0];
        return $primerEspacio['fin'] + 1;
    }

    /**
     * Corta la imagen inferior en partes automáticas (izquierda, centro, derecha) y las guarda.
     */
    public function guardarCortesImagenAutomaticoDesdeImg($img, string $dirSalida, string $basename): array
    {
        $imgWidth = imagesx($img);
        $imgHeight = imagesy($img);

        // Convertir a escala de grises si no lo está
        imagefilter($img, IMG_FILTER_GRAYSCALE);

        // Análisis vertical
        $histogramaX = array_fill(0, $imgWidth, 0);
        for ($x = 0; $x < $imgWidth; $x++) {
            $suma = 0;
            for ($y = 0; $y < $imgHeight; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $gray = $rgb & 0xFF;
                if ($gray > 200) $suma++;
            }
            $histogramaX[$x] = $suma;
        }

        $limites = [];
        $umbral = (int)($imgHeight * 0.8);
        $enBlanco = false;
        for ($x = 0; $x < $imgWidth; $x++) {
            if (!$enBlanco && $histogramaX[$x] >= $umbral) {
                $inicio = $x;
                $enBlanco = true;
            } elseif ($enBlanco && $histogramaX[$x] < $umbral) {
                $fin = $x;
                if (($fin - $inicio) > 30) {
                    $limites[] = [$inicio, $fin];
                }
                $enBlanco = false;
            }
        }

        // Generar OCR para confirmar bloque central
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

        $corteCentroX1 = null;
        $corteCentroX2 = null;

        foreach ($bloquesOCR as $palabras) {
            $primerNumIdx = null;
            for ($i = 0; $i < count($palabras); $i++) {
                $t = preg_replace('/[^\d]/', '', $palabras[$i]['text']);
                if (is_numeric($t) && $t !== '') {
                    $primerNumIdx = $i;
                    break;
                }
            }
            if ($primerNumIdx === null) continue;

            $ultimoNumIdx = null;
            for ($j = count($palabras) - 1; $j > $primerNumIdx; $j--) {
                $t = preg_replace('/[^\d]/', '', $palabras[$j]['text']);
                if (is_numeric($t) && $t !== '') {
                    $ultimoNumIdx = $j;
                    break;
                }
            }
            if ($ultimoNumIdx === null) continue;

            if ($ultimoNumIdx - $primerNumIdx < 2) continue;

            $corteCentroX1 = $palabras[$primerNumIdx]['left'];
            $corteCentroX2 = $palabras[$ultimoNumIdx]['right'];
            break;
        }

        if (!$corteCentroX1 || !$corteCentroX2 || $corteCentroX2 <= $corteCentroX1) {
            throw new \Exception("No se detectó un bloque central con patrón número – texto – número.");
        }

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
}
