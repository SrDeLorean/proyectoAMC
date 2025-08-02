<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemporadaCompetencia;
use App\Models\Temporada;
use App\Models\Competencia;

class TemporadaCompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        $temporada = Temporada::where('nombre', 'Temporada 2025')->first();

        $competencias = Competencia::pluck('id', 'nombre'); // [ 'Liga Profesional' => 1, ... ]

        TemporadaCompetencia::create([
            'nombre' => '2025 - Liga Profesional',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['AMC ProLeague'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Liga Elite',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Liga Elite'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Liga Ascenso',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Liga Ascenso'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Liga Anfa',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Liga Anfa'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Relámpago',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Relampago'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);


    }
}
