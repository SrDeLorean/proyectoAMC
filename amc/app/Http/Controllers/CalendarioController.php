<?php

// app/Http/Controllers/CalendarioController.php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function generateByTemporadaCompetencia($id)
    {
        $temporadaCompetencia = TemporadaCompetencia::findOrFail($id);

        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $id)
            ->pluck('id_equipo')
            ->toArray();

        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            return back()->with('error', 'Se requieren al menos 2 equipos para generar un calendario.');
        }

        // Si número impar, agregamos un "bye"
        $esImpar = $totalEquipos % 2 !== 0;
        if ($esImpar) {
            $equipos[] = null; // bye
            $totalEquipos++;
        }

        $jornadas = $totalEquipos - 1;
        $partidosPorJornada = $totalEquipos / 2;

        $partidos = [];

        for ($jornada = 1; $jornada <= $jornadas; $jornada++) {
            for ($i = 0; $i < $partidosPorJornada; $i++) {
                $local = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                if ($local !== null && $visitante !== null) {
                    $partidos[] = [
                        'id_temporadacompetencia' => $id,
                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'jornada' => $jornada,
                    ];
                }
            }

            // Rotar equipos (excepto el primero)
            $equipoFijo = array_shift($equipos);
            array_unshift($equipos, $equipos[$totalEquipos - 2]);
            for ($i = $totalEquipos - 2; $i > 1; $i--) {
                $equipos[$i] = $equipos[$i - 1];
            }
            $equipos[1] = $equipoFijo;
        }

        // Opcional: doble ronda (ida y vuelta)
        $partidosVuelta = [];
        foreach ($partidos as $p) {
            $partidosVuelta[] = [
                'id_temporadacompetencia' => $p['id_temporadacompetencia'],
                'equipo_local_id' => $p['equipo_visitante_id'],
                'equipo_visitante_id' => $p['equipo_local_id'],
                'jornada' => $p['jornada'] + $jornadas, // jornada posterior
            ];
        }

        Calendario::insert(array_merge($partidos, $partidosVuelta));

        return back()->with('success', 'Calendario con jornadas generado exitosamente.');
    }

}
