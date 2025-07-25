<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;

class TemporadaEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar la TemporadaCompetencia de "Primera División"
        $temporadaCompetencia = TemporadaCompetencia::whereHas('competencia', function ($query) {
            $query->where('nombre', 'AMC ProLeague');
        })->first();

        if (!$temporadaCompetencia) {
            $this->command->error('No se encontró una TemporadaCompetencia para "Primera División". Asegúrate de haber ejecutado los seeders correctamente.');
            return;
        }

        // Obtener todos los equipos
        $equipos = Equipo::all();

        foreach ($equipos as $equipo) {
            TemporadaEquipo::create([
                'id_temporadacompetencia' => $temporadaCompetencia->id,
                'id_equipo' => $equipo->id,
            ]);
        }

        $this->command->info('Todos los equipos fueron afiliados a la competencia "Primera División".');
    }
}
