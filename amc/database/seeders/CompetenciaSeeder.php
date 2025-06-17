<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competencia;
use Illuminate\Support\Facades\Hash;

class CompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        Competencia::create([
            'nombre' => 'Liga Profesional',
        ]);

        Competencia::create([
            'nombre' => 'Primera División',
        ]);

        Competencia::create([
            'nombre' => 'Segunda División',
        ]);

        Competencia::create([
            'nombre' => 'Tercera División',
        ]);

        Competencia::create([
            'nombre' => 'Relampago',
        ]);
    }
}
