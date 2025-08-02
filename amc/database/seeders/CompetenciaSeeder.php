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
            'nombre' => 'AMC ProLeague',
            'logo' => 'images/competencias/AMC-PROLEAGUE.png', // Ruta del logo
        ]);

        Competencia::create([
            'nombre' => 'Liga Elite',
            'logo' => 'images/competencias/AMC-ELITE.png', // Ruta del logo
        ]);

        Competencia::create([
            'nombre' => 'Liga Ascenso',
            'logo' => 'images/competencias/AMC-ASCENSO.png', // Ruta del logo
        ]);

        Competencia::create([
            'nombre' => 'Liga Anfa',
            'logo' => 'images/competencias/AMC-ANFA.png', // Ruta del logo
        ]);

        Competencia::create([
            'nombre' => 'Copa Chile AMC',
            'logo' => 'images/competencias/AMC-COPACHILE.png', // Ruta del logo
        ]);

        Competencia::create([
            'nombre' => 'Relampago',
            'logo' => 'images/competencias/AMC-RELAMPAGO.png', // Ruta del logo
        ]);
    }
}
