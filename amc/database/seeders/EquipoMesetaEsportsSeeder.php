<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoMesetaEsportsSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-4-2']);

        $dt = User::firstOrCreate(
            ['id_ea' => 'fabianandresiet'],
            [
                'name' => 'Fabian Pino',
                'email' => 'fabianandresiet@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('MESETA eSports');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'MESETA eSports',
            'color_primario' => '#333333',
            'color_secundario' => '#CCCCCC',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dt->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [1, 'liKzQx exe', 'Portero'],
            [2, 'nachoxt_1702', 'Defensa'],
            [3, 'boky_Lda', 'Defensa'],
            [4, 'luchobalcar3', 'Defensa'],
            [5, 'Atr_turro', 'Defensa'],
            [6, 'ElMati700', 'Defensa'],
            [7, 'Shishi_seba14', 'Defensa'],
            [8, 'not_joakoossj', 'Mediocampista'],
            [9, 'MattyNikolas', 'Mediocampista'],
            [10, 'comeluche7', 'Mediocampista'],
            [11, 'AQUABARBER', 'Mediocampista'],
            [12, 'x_Chris7ian10_x', 'Mediocampista'],
            [13, 'fabianandresiet', 'Delantero'],
            [14, 'ellbandio', 'Delantero'],
        ];

        $mapPos = [
            'portero' => 'POR',
            'defensa' => 'DFC',
            'mediocampista' => 'MC',
            'medio campista' => 'MC',
            'volante' => 'MI',
            'delantero' => 'DC',
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => trim($id_ea)],
                [
                    'name' => trim($id_ea),
                    'email' => $emailify($id_ea),
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            Plantilla::firstOrCreate([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => $mapPos[strtolower(trim($posicion))] ?? 'MC',
            ]);
        }
    }
}
