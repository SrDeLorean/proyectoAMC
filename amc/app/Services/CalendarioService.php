<?php

namespace App\Services;

use App\Models\Calendario;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;
use Carbon\Carbon;

class CalendarioService
{
    public function generateCalendar($idTemporadaCompetencia, bool $idaYVuelta = true): array
    {
        $temporadaCompetencia = TemporadaCompetencia::with('temporada')->findOrFail($idTemporadaCompetencia);

        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $idTemporadaCompetencia)
            ->pluck('id_equipo')
            ->toArray();

        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            return ['error' => 'Se requieren al menos 2 equipos para generar un calendario.'];
        }

        $esImpar = $totalEquipos % 2 !== 0;
        if ($esImpar) {
            $equipos[] = null; // "bye"
            $totalEquipos++;
        }

        $jornadas = $totalEquipos - 1;
        $partidosPorJornada = $totalEquipos / 2;

        $partidos = [];

        // Generar partidos ida
        for ($jornada = 1; $jornada <= $jornadas; $jornada++) {
            for ($i = 0; $i < $partidosPorJornada; $i++) {
                $local = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                if ($local !== null && $visitante !== null) {
                    $partidos[] = [
                        'id_temporadacompetencia' => $idTemporadaCompetencia,
                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'jornada' => $jornada,
                    ];
                }
            }

            // Rotación round-robin (equipo 0 fijo)
            $pivot = array_splice($equipos, 1);
            array_unshift($pivot, array_pop($pivot));
            $equipos = array_merge([$equipos[0]], $pivot);
        }

        // Si es ida y vuelta, generar partidos de vuelta intercambiando local y visitante
        if ($idaYVuelta) {
            $partidosVuelta = [];
            foreach ($partidos as $p) {
                $partidosVuelta[] = [
                    'id_temporadacompetencia' => $p['id_temporadacompetencia'],
                    'equipo_local_id' => $p['equipo_visitante_id'],
                    'equipo_visitante_id' => $p['equipo_local_id'],
                    'jornada' => $p['jornada'] + $jornadas,
                ];
            }
            $todosPartidos = array_merge($partidos, $partidosVuelta);
        } else {
            $todosPartidos = $partidos;
        }

        // Asignar fechas y horas para cada jornada
        $fechaInicio = Carbon::parse($temporadaCompetencia->fecha_inicio);
        $diasPermitidos = ['Monday', 'Tuesday', 'Thursday'];
        $horas = ['23:00:00', '23:30:00'];

        $jornadaFechaHora = [];
        $jornadaActual = 1;
        $fechaActual = $fechaInicio->copy();

        $totalJornadas = $idaYVuelta ? $jornadas * 2 : $jornadas;

        while ($jornadaActual <= $totalJornadas) {
            if (in_array($fechaActual->englishDayOfWeek, $diasPermitidos)) {
                foreach ($horas as $hora) {
                    if ($jornadaActual > $totalJornadas) break;
                    $jornadaFechaHora[$jornadaActual] = [
                        'fecha' => $fechaActual->toDateString(),
                        'hora' => $hora,
                    ];
                    $jornadaActual++;
                }
            }
            $fechaActual->addDay();
        }

        // Asignar fecha y hora a cada partido
        foreach ($todosPartidos as &$partido) {
            $info = $jornadaFechaHora[$partido['jornada']] ?? ['fecha' => null, 'hora' => null];
            $partido['fecha'] = $info['fecha'];
            $partido['hora'] = $info['hora'];
        }
        unset($partido);

        // Insertar partidos en la BD
        Calendario::insert($todosPartidos);

        return ['success' => true, 'message' => 'Calendario generado exitosamente con fechas y horas.'];
    }
}
