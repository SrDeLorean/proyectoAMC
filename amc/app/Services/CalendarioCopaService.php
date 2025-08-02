<?php

namespace App\Services;

use App\Models\Calendario;
use App\Models\Equipo;
use Carbon\Carbon;
use Exception;

class CalendarioCopaService
{
    private $nombresRondas = [
        5 => ['16avos', 'Octavos', 'Cuartos', 'Semifinal', 'Final'],
        4 => ['Octavos', 'Cuartos', 'Semifinal', 'Final'],
        3 => ['Cuartos', 'Semifinal', 'Final'],
        2 => ['Semifinal', 'Final'],
        1 => ['Final'],
    ];

    private function obtenerNombreRonda(int $totalRondas, int $rondaActual): string
    {
        if (!isset($this->nombresRondas[$totalRondas])) {
            return "Ronda {$rondaActual}";
        }

        $nombres = $this->nombresRondas[$totalRondas];

        if ($rondaActual < 1 || $rondaActual > count($nombres)) {
            return "Ronda {$rondaActual}";
        }

        return $nombres[$rondaActual - 1];
    }

    private function siguienteFechaDisponible(Carbon $fechaActual): Carbon
    {
        // Avanza fecha hasta que sea lunes(1), martes(2) o jueves(4)
        while (!in_array($fechaActual->dayOfWeekIso, [1, 2, 4])) {
            $fechaActual->addDay();
        }
        return $fechaActual;
    }

    private function calcularRondas(int $numEquipos): int
    {
        return (int) ceil(log($numEquipos, 2));
    }

    /**
     * Genera calendario con partidos ida y vuelta, todos los partidos de una ronda el mismo día.
     *
     * @param array $equipos
     * @param int $id_temporadacompetencia
     * @param string|Carbon $fechaInicio
     * @return array
     */
    public function generarEmparejamientoDirecto(array $equipos, int $id_temporadacompetencia, $fechaInicio): array
    {
        try {
            if (count($equipos) < 2) {
                return ['error' => 'Se necesitan al menos 2 equipos para generar la copa.'];
            }

            $equipoPorDeterminar = Equipo::where('nombre', 'POR DETERMINAR')->first();
            if (!$equipoPorDeterminar) {
                return ['error' => 'No existe el equipo "POR DETERMINAR". Por favor, créalo en la base de datos.'];
            }
            $equipoPorDeterminarId = $equipoPorDeterminar->id;

            Calendario::where('id_temporadacompetencia', $id_temporadacompetencia)->delete();

            shuffle($equipos);
            $rondas = $this->calcularRondas(count($equipos));
            $fechaActual = $fechaInicio instanceof Carbon ? $fechaInicio->copy() : new Carbon($fechaInicio);

            $equiposRonda = $equipos;

            for ($ronda = 1; $ronda <= $rondas; $ronda++) {
                if (count($equiposRonda) < 2) break;

                // Si hay equipo sin pareja, pasa directo a la siguiente ronda
                if (count($equiposRonda) % 2 !== 0) {
                    $paseDirecto = array_pop($equiposRonda);
                } else {
                    $paseDirecto = null;
                }

                $nombreRonda = $this->obtenerNombreRonda($rondas, $ronda);

                // Todos los partidos de esta ronda se juegan este día
                $fechaActual = $this->siguienteFechaDisponible($fechaActual);

                // Generar partidos ida y vuelta para todos los encuentros de esta ronda
                for ($i = 0; $i < count($equiposRonda); $i += 2) {
                    if (!isset($equiposRonda[$i + 1])) continue;

                    $local = $equiposRonda[$i];
                    $visitante = $equiposRonda[$i + 1];

                    Calendario::create([
                        'id_temporadacompetencia' => $id_temporadacompetencia,
                        'id_equipo_local' => $local,
                        'id_equipo_visitante' => $visitante,
                        'fecha' => $fechaActual->toDateString(),
                        'hora' => '23:00',
                        'jornada' => "{$nombreRonda} - Ida",
                    ]);

                    Calendario::create([
                        'id_temporadacompetencia' => $id_temporadacompetencia,
                        'id_equipo_local' => $visitante,
                        'id_equipo_visitante' => $local,
                        'fecha' => $fechaActual->toDateString(),
                        'hora' => '23:30',
                        'jornada' => "{$nombreRonda} - Vuelta",
                    ]);
                }

                // Preparar equipos para la siguiente ronda, usando "POR DETERMINAR"
                $equiposRonda = array_fill(0, (int)(count($equiposRonda)/2) + ($paseDirecto ? 1 : 0), $equipoPorDeterminarId);

                // Agregar equipo que pasó directo si existe
                if ($paseDirecto) {
                    $equiposRonda[] = $paseDirecto;
                }

                // Avanzar fecha para la próxima ronda
                $fechaActual->addDay();
            }

            return ['message' => 'Calendario de copa (emparejamiento directo) generado correctamente.'];

        } catch (Exception $e) {
            return ['error' => 'Error al generar calendario copa: ' . $e->getMessage()];
        }
    }

    /**
     * Genera calendario con ventaja, partidos ida y vuelta el mismo día por ronda.
     *
     * @param array $equipos
     * @param int $id_temporadacompetencia
     * @param string|Carbon $fechaInicio
     * @return array
     */
    public function generarConVentaja(array $equipos, int $id_temporadacompetencia, $fechaInicio): array
    {
        try {
            if (count($equipos) < 3) {
                return ['error' => 'Se necesitan al menos 3 equipos para esta modalidad de copa con ventaja.'];
            }

            $equipoPorDeterminar = Equipo::where('nombre', 'POR DETERMINAR')->first();
            if (!$equipoPorDeterminar) {
                return ['error' => 'No existe el equipo "POR DETERMINAR". Por favor, créalo en la base de datos.'];
            }
            $equipoPorDeterminarId = $equipoPorDeterminar->id;

            Calendario::where('id_temporadacompetencia', $id_temporadacompetencia)->delete();

            $equipoFinal = array_shift($equipos);      // Va directo a la final
            $equipoSemifinal = array_shift($equipos); // Va directo a semifinal

            shuffle($equipos);

            $fechaActual = $fechaInicio instanceof Carbon ? $fechaInicio->copy() : new Carbon($fechaInicio);

            // Fase de Cuartos (equipos restantes)
            $nombreFase = 'Cuartos de Final';

            // Todos los partidos de cuartos se juegan el mismo día
            $fechaActual = $this->siguienteFechaDisponible($fechaActual);

            for ($i = 0; $i < count($equipos); $i += 2) {
                if (!isset($equipos[$i + 1])) continue;

                $local = $equipos[$i];
                $visitante = $equipos[$i + 1];

                Calendario::create([
                    'id_temporadacompetencia' => $id_temporadacompetencia,
                    'id_equipo_local' => $local,
                    'id_equipo_visitante' => $visitante,
                    'fecha' => $fechaActual->toDateString(),
                    'hora' => '23:00',
                    'jornada' => "$nombreFase - Ida",
                ]);

                Calendario::create([
                    'id_temporadacompetencia' => $id_temporadacompetencia,
                    'id_equipo_local' => $visitante,
                    'id_equipo_visitante' => $local,
                    'fecha' => $fechaActual->toDateString(),
                    'hora' => '23:30',
                    'jornada' => "$nombreFase - Vuelta",
                ]);
            }

            // Avanzar fecha para semifinal
            $fechaActual->addDay();
            $fechaActual = $this->siguienteFechaDisponible($fechaActual);

            // Semifinal
            Calendario::create([
                'id_temporadacompetencia' => $id_temporadacompetencia,
                'id_equipo_local' => $equipoSemifinal,
                'id_equipo_visitante' => $equipoPorDeterminarId,
                'fecha' => $fechaActual->toDateString(),
                'hora' => '23:00',
                'jornada' => "Semifinal - Ida",
            ]);
            Calendario::create([
                'id_temporadacompetencia' => $id_temporadacompetencia,
                'id_equipo_local' => $equipoPorDeterminarId,
                'id_equipo_visitante' => $equipoSemifinal,
                'fecha' => $fechaActual->toDateString(),
                'hora' => '23:30',
                'jornada' => "Semifinal - Vuelta",
            ]);

            // Avanzar fecha para final
            $fechaActual->addDay();
            $fechaActual = $this->siguienteFechaDisponible($fechaActual);

            // Final
            Calendario::create([
                'id_temporadacompetencia' => $id_temporadacompetencia,
                'id_equipo_local' => $equipoFinal,
                'id_equipo_visitante' => $equipoPorDeterminarId,
                'fecha' => $fechaActual->toDateString(),
                'hora' => '23:00',
                'jornada' => "Final - Ida",
            ]);
            Calendario::create([
                'id_temporadacompetencia' => $id_temporadacompetencia,
                'id_equipo_local' => $equipoPorDeterminarId,
                'id_equipo_visitante' => $equipoFinal,
                'fecha' => $fechaActual->toDateString(),
                'hora' => '23:30',
                'jornada' => "Final - Vuelta",
            ]);

            return ['message' => 'Calendario de copa con ventaja generado correctamente.'];

        } catch (Exception $e) {
            return ['error' => 'Error al generar calendario copa con ventaja: ' . $e->getMessage()];
        }
    }
}
