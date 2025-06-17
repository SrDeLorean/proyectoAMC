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
            'id_competencia' => $competencias['Liga Profesional'],
            'fecha_inicio' => '2025-03-01',
            'fecha_termino' => '2025-10-15',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Primera División',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Primera División'],
            'fecha_inicio' => '2025-03-05',
            'fecha_termino' => '2025-10-20',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Segunda División',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Segunda División'],
            'fecha_inicio' => '2025-03-10',
            'fecha_termino' => '2025-10-25',
        ]);

        TemporadaCompetencia::create([
            'nombre' => '2025 - Relámpago de Verano',
            'id_temporada' => $temporada->id,
            'id_competencia' => $competencias['Relampago'],
            'fecha_inicio' => '2025-01-10',
            'fecha_termino' => '2025-02-10',
        ]);
    }
}
