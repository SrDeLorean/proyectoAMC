<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstadisticaEquipo;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Log;

class EstadisticaEquipoController extends Controller
{
    public function subirDesdeImagen(Request $request)
    {
        try {
            $validated = $request->validate([
                'imagen' => 'required|image|max:5120',
                'calendario_id' => 'required|exists:calendarios,id',
                'equipo_id' => 'required|exists:equipos,id',
            ]);

            if (!$request->hasFile('imagen') || !$request->file('imagen')->isValid()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['imagen' => ['No se recibió una imagen válida.']],
                ], 422);
            }

            // Obtener ruta de la imagen subida temporalmente
            $archivoOriginal = $request->file('imagen')->getRealPath();

            // Preprocesar imagen con GD: convertir a escala de grises
            $this->grayscaleImage($archivoOriginal);

            // Ejecutar OCR con configuración mejorada
            $texto = (new TesseractOCR($archivoOriginal))
                ->lang('spa')
                ->psm(6)           // Asume bloque uniforme de texto
                ->whitelist('0123456789.%:abcdefghijklmnopqrstuvwxyzáéíóúñü ')
                ->run();

            // Normalizar texto para corregir confusiones típicas OCR
            $texto = $this->normalizarTexto($texto);

            // Extraer estadísticas del texto
            $estadisticas = $this->parsearTexto($texto);

            if (empty($estadisticas)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['imagen' => ['No se pudieron extraer estadísticas de la imagen.']],
                ], 422);
            }

            EstadisticaEquipo::updateOrCreate(
                [
                    'calendario_id' => $validated['calendario_id'],
                    'equipo_id' => $validated['equipo_id'],
                ],
                $estadisticas
            );

            return response()->json([
                'success' => true,
                'message' => 'Estadísticas subidas correctamente.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al subir estadísticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Error interno del servidor.']],
            ], 500);
        }
    }

    private function grayscaleImage(string $path): void
    {
        $info = getimagesize($path);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $image = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($path);
                break;
            default:
                throw new \Exception("Tipo de imagen no soportado para preprocesar.");
        }

        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagejpeg($image, $path);
        imagedestroy($image);
    }

    private function normalizarTexto(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = preg_replace('/[^a-z0-9áéíóúñü %:\.\r\n]/u', '', $texto);
        $texto = str_replace(['o', 'i', 'l'], ['0', '1', '1'], $texto);
        $texto = preg_replace('/[ \t]+/', ' ', $texto);
        $texto = preg_replace('/\r\n|\r|\n/', "\n", $texto);
        return trim($texto);
    }

    private function parsearTexto(string $texto): array
    {
        $stats = [];

        $patterns = [
            'posesion' => '/posesión[\s:]*([\d]{1,3})%/i',
            'tiros' => '/tiros[\s:]*([\d]+)/i',
            'goles_esperados' => '/goles\s+esperados[\s:]*([\d\.]+)/i',
            'regates_exito' => '/regates\s*exitosos[\s:]*([\d]{1,3})%/i',
            'precision_tiros' => '/precisión\s*tiros[\s:]*([\d]{1,3})%/i',
            'precision_pases' => '/precisión\s*pases[\s:]*([\d]{1,3})%/i',
            'recuperacion_balon' => '/recuperación\s*balón[\s:]*([\d]+)/i',
            'entradas' => '/entradas[\s:]*([\d]+)/i',
            'entradas_exito' => '/entradas\s*con\s*éxito[\s:]*([\d]+)/i',
            'recuperaciones' => '/recuperaciones[\s:]*([\d]+)/i',
            'atajadas' => '/atajadas[\s:]*([\d]+)/i',
            'faltas_cometidas' => '/faltas\s*cometidas[\s:]*([\d]+)/i',
            'fuera_de_juego' => '/fuera\s*de\s*juego[\s:]*([\d]+)/i',
            'tiros_esquina' => '/tiros\s*de\s*esquina[\s:]*([\d]+)/i',
            'tiros_libres' => '/tiros\s*libres[\s:]*([\d]+)/i',
            'penales' => '/penales[\s:]*([\d]+)/i',
            'tarjetas_amarillas' => '/tarjetas\s*amarillas[\s:]*([\d]+)/i',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $texto, $matches)) {
                $stats[$key] = $key === 'goles_esperados' ? (float)$matches[1] : (int)$matches[1];
            }
        }

        return $stats;
    }
}
