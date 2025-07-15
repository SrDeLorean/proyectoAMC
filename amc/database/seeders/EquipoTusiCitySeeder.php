<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoTusiCitySeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Mati_Sandoval10'],
            [
                'name' => 'Mati_Sandoval10',
                'email' => 'mati_sandoval10@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = $dueno; // mismo usuario que el dueño

        $logoNombre = strtolower("Tusi City");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'rosado' => '#FFC0CB',
            'negro' => '#000000',
            // agrega más colores si necesitas
        ];

        $colorPrimario = 'Rosado';
        $colorSecundario = 'Negro';

        $equipo = Equipo::create([
            'nombre' => 'Tusi City',
            'color_primario' => $coloresHex[strtolower($colorPrimario)] ?? '#000000',
            'color_secundario' => $coloresHex[strtolower($colorSecundario)] ?? '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [35, 'Boricuabueno22pr', 'jugador', 'GK'],
            [13, 'Viable_Corn77', 'jugador', 'GK'],
            [4, 'Gtizzy_', 'jugador', 'DFC'],
            [29, 'Arielozky', 'jugador', 'DFC'],
            [17, 'Benja_7_elpro', 'jugador', 'DFC'],
            [26, 'Tortugabiena', 'jugador', 'DFC'],
            [15, 'EduPeru15', 'jugador', 'DFC'],
            [19, 'Pelao1018', 'jugador', 'DFC'],
            [45, 'MixelCrazy', 'jugador', 'MCD'],
            [5, 'Kasiishumi', 'jugador', 'MCD'],
            [80, 'Vdoobz', 'jugador', 'MCD'],
            [8, 'Br0ly_NY', 'Sub-DT', 'MCD'],
            [7, 'El_D1stinto_10', 'jugador', 'MCO'],
            [30, 'SnakeBlack70', 'jugador', 'MCO'],
            [14, 'Mellarep_12', 'jugador', 'MI'],
            [11, 'xAstroworld_|33|', 'jugador', 'MI'],
            [16, 'A_1gn4c110', 'jugador', 'MD'],
            [23, 'Keblard23', 'jugador', 'MD'],
            [99, 'Dagessyy-cL', 'Sub-DT', 'DC'],
            [9, 'vinniv10', 'jugador', 'DC'],
            [3, 'VKG IRANZERA', 'jugador', 'DC'],
            [10, 'Mati_Sandoval10', 'DT', 'DC'],
        ];

        foreach ($jugadores as [$numero, $id_ea, $rol, $posicion]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => strtolower(str_replace(['|', ' '], '', $id_ea)) . '@mail.com',
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ],
            );

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => $rol,
                'posicion' => strtoupper($posicion),
            ]);
        }
    }
}
