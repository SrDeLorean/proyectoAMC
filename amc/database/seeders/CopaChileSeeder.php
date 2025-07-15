<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temporada;
use App\Models\Competencia;
use App\Models\Equipo;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;

class CopaChileSeeder extends Seeder
{
    public function run(): void
    {
        $temporada = Temporada::where('nombre', 'Temporada 2025')->firstOrFail();
        $competencia = Competencia::where('nombre', 'Copa Chile AMC')->firstOrFail();

        $grupos = [
            'Copa Chile #1 - Grupo A' => [
                'Successors',
                'INFAMES ESPORT',
                'Sangre Nueva',
                'Oldhouse FC',
                'Espartanos CL',
            ],
            'Copa Chile #1 - Grupo B' => [
                'Juventud Avance eSports',
                'FC Chanchitos',
                'MESETA eSports',
                'Rompiendo Redes Esp',
                'Toros Fc',
            ],
            'Copa Chile #1 - Grupo C' => [
                'United Forever',
                'Wanderers Esports',
                'LaRojita2025',
                'El Volcán FC',
                'Exiliados FC',
            ],
            'Copa Chile #1 - Grupo D' => [
                'Muni Muni eSports',
                'Cobresal',
                'Resistencia SPS',
                'Concepción City Club eSports',
                'Tusi City',
            ],
            'Copa Chile #1 - Grupo E' => [
                'Deportes Concepción',
                'Aurinegro Esports',
                'BlackDog',
                'Los Cacas',
                'Furia Roja',
            ],
            'Copa Chile #1 - Grupo F' => [
                'Madness',
                'Deathgunners FC',
                'Barrabases eSports',
                'Basofias FC',
                'Bluelock',
            ],
        ];

        foreach ($grupos as $nombreGrupo => $nombresEquipos) {
            $temporadaCompetencia = TemporadaCompetencia::create([
                'nombre' => $nombreGrupo,
                'id_temporada' => $temporada->id,
                'id_competencia' => $competencia->id,
                'fecha_inicio' => '2025-07-07',
                'fecha_termino' => '2025-07-31',
            ]);

            foreach ($nombresEquipos as $nombreEquipo) {
                $equipo = Equipo::withTrashed()
                    ->whereRaw('BINARY nombre = ?', [$nombreEquipo])
                    ->first();

                if ($equipo) {
                    TemporadaEquipo::create([
                        'id_temporadacompetencia' => $temporadaCompetencia->id,
                        'id_equipo' => $equipo->id,
                    ]);
                } else {
                    $this->command->warn("❌ No se encontró el equipo (comparación exacta): '$nombreEquipo'");
                }
            }
        }

        $this->command->info('✅ Grupos de Copa Chile creados con nombres exactos.');
    }
}
