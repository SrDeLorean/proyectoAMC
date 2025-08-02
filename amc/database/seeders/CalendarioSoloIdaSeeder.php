<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calendario;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;
use Carbon\Carbon;

class CalendarioSoloIdaSeeder extends Seeder
{
    public int $id;
    public string $fechaInicio;

    public function run(): void
    {
        if (!isset($this->id) || !isset($this->fechaInicio)) {
            echo "❌ Debes definir el ID de TemporadaCompetencia y la fecha de inicio.\n";
            return;
        }

        $temporadaCompetencia = TemporadaCompetencia::with('temporada')->findOrFail($this->id);

        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $this->id)
            ->pluck('id_equipo')
            ->toArray();

        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            echo "⚠️ Solo hay {$totalEquipos} equipo(s), se requieren al menos 2 para generar calendario.\n";
            return;
        }

        // Si es impar, se agrega un "bye"
        if ($totalEquipos % 2 !== 0) {
            $equipos[] = null;
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
                        'id_temporadacompetencia' => $this->id,
                        'id_equipo_local' => $local,
                        'id_equipo_visitante' => $visitante,
                        'jornada' => $jornada,
                    ];
                }
            }

            // Round-robin: rotar equipos (equipo 0 fijo)
            $pivot = array_splice($equipos, 1);
            array_unshift($pivot, array_pop($pivot));
            $equipos = array_merge([$equipos[0]], $pivot);
        }

        // Asignar fechas y horas correctamente respetando la fecha de inicio
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $diasPermitidos = ['Monday', 'Tuesday', 'Thursday'];
        $horas = ['23:00:00', '23:30:00'];

        $jornadaFechaHora = [];
        $jornadaActual = 1;
        $fechaActual = $fechaInicio->copy();

        while ($jornadaActual <= $jornadas) {
            if (!in_array($fechaActual->englishDayOfWeek, $diasPermitidos)) {
                $fechaActual->addDay();
                continue;
            }

            foreach ($horas as $hora) {
                if ($jornadaActual > $jornadas) break;

                $jornadaFechaHora[$jornadaActual] = [
                    'fecha' => $fechaActual->toDateString(),
                    'hora' => $hora,
                ];
                $jornadaActual++;
            }

            $fechaActual->addDay();
        }

        foreach ($partidos as &$partido) {
            $info = $jornadaFechaHora[$partido['jornada']] ?? ['fecha' => null, 'hora' => null];
            $partido['fecha'] = $info['fecha'];
            $partido['hora'] = $info['hora'];
        }

        Calendario::insert($partidos);

        echo "✅ Calendario SOLO IDA generado para TemporadaCompetencia ID {$this->id} desde {$this->fechaInicio}\n";
    }
}
