<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Temporada;
use Illuminate\Support\Facades\Hash;

class TemporadaSeeder extends Seeder
{
    public function run(): void
    {
        Temporada::create([
            'nombre' => 'Temporada 2025',
        ]);
    }
}
