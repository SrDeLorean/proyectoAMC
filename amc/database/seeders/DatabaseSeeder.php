<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\FormacionSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\EquipoSeeder;
use Database\Seeders\TemporadaSeeder;
use Database\Seeders\CompetenciaSeeder;
use Database\Seeders\TemporadaCompetenciaSeeder;
use Database\Seeders\TemporadaEquipoSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(FormacionSeeder::class);
        $this->call(EquipoSeeder::class);
        $this->call(TemporadaSeeder::class);
        $this->call(CompetenciaSeeder::class);
        $this->call(TemporadaCompetenciaSeeder::class);
        $this->call(TemporadaEquipoSeeder::class);
    }
}
