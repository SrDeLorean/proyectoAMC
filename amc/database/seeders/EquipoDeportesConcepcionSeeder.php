<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoDeportesConcepcionSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'RiperCL'],
            [
                'name' => 'RiperCL',
                'email' => 'ripercl@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = $dueno; // mismo usuario

        $logoNombre = strtolower("Deportes Concepción");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'morado' => '#800080',
            'blanco' => '#FFFFFF',
            'negro' => '#000000',
            // Agrega más si es necesario
        ];

        $colorPrimario = 'morado';
        $colorSecundario = 'blanco';

        $equipo = Equipo::create([
            'nombre' => 'Deportes Concepción',
            'color_primario' => $coloresHex[strtolower($colorPrimario)] ?? '#000000',
            'color_secundario' => $coloresHex[strtolower($colorSecundario)] ?? '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            ['wat_ii', 'Portero'],
            ['D3T0NA0_KLO-_2', 'Portero'],
            ['DiegolFc10', 'Defensa'],
            ['VIKINGHOVDING22', 'Defensa'],
            ['sebaurtullaa', 'Defensa'],
            ['RiperCL', 'Defensa'],
            ['Mk808_', 'Defensa'],
            ['benjyyJR', 'Volante'],
            ['Loyalty_CLI17I', 'Volante'],
            ['BvnnjitaaaJR', 'Volante'],
            ['BorisWSchulz10', 'Mediocampista'],
            ['marcouski20', 'Mediocampista'],
            ['L4F3RT3_10', 'Mediocampista'],
            ['_Underjova_I', 'Mediocampista'],
            ['PinTorAustriako', 'Delantero'],
            ['Kikin__2002', 'Delantero'],
            ['Franco_23__', 'Delantero'],
        ];

        $numero = 1;
        foreach ($jugadores as [$id_ea, $rol]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => strtolower(str_replace(['|', ' ', '__'], '', $id_ea)) . '@mail.com',
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ],
            );

            $posicion = match (strtolower($rol)) {
                'portero' => 'POR',
                'defensa' => 'DFC',
                'volante', 'mediocampista' => 'MC',
                'delantero' => 'DC',
                default => 'MC',
            };

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero++,
                'rol' => 'jugador',
                'posicion' => $posicion,
            ]);
        }
    }
}
