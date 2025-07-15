<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders base
        $this->call([
            UserSeeder::class,
            FormacionSeeder::class,
            TemporadaSeeder::class,
            CompetenciaSeeder::class,
            TemporadaCompetenciaSeeder::class,
        ]);

        // Seeders de equipos personalizados
        $this->call([
            EquipoBlackDogsSeeder::class,
            EquipoAurinegroSeeder::class,
            EquipoBasofiasSeeder::class,
            EquipoConcepcionCitySeeder::class,
            EquipoDeathgunnersSeeder::class,
            EquipoDeportesConcepcionSeeder::class,
            EquipoElVolcanSeeder::class,
            EquipoEspartanosClSeeder::class,
            EquipoFcChanchitosSeeder::class,
            EquipoFuriaRojaSeeder::class,
            EquipoInfamesEsportSeeder::class,
            EquipoJuventudAvanceSeeder::class,
            EquipoLosCacasSeeder::class,
            EquipoMesetaEsportsSeeder::class,
            EquipoMuniMuniSeeder::class,
            EquipoOldhouseFcSeeder::class,
            EquipoResistenciaSpsSeeder::class,
            EquipoSuccessorsSeeder::class,
            EquipoTusiCitySeeder::class,
            EquipoWanderersSeeder::class,
            EquipoSangreNuevaSeeder::class,
            EquipoMadnessSeeder::class,
            EquipoBluelockSeeder::class,
            EquipoLaRojita2025Seeder::class,
            EquipoUnitedForeverSeeder::class,
            EquipoBarrabasesSeeder::class,
            EquipoTorosSeeder::class,
            EquipoCobresalSeeder::class,
            EquipoRompiendoRedesEspSeeder::class,
            EquipoExiliadosFCSeeder::class,
        ]);

        // Seeders finales
        $this->call([
            CopaChileSeeder::class,
        ]);

        $ids = [6, 7, 8, 9, 10, 11];

        foreach ($ids as $id) {
            $calendarioSeeder = new \Database\Seeders\CalendarioSoloIdaSeeder();
            $calendarioSeeder->id = $id;
            $calendarioSeeder->fechaInicio = '2025-07-07'; // Fecha de inicio deseada
            $calendarioSeeder->run();
        }


    }
}
