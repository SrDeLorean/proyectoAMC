<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoInfamesEsportSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'DO_G4RRINCHA'],
            [
                'name' => 'DO_G4RRINCHA',
                'email' => 'do_g4rrincha@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $codt = User::firstOrCreate(
            ['id_ea' => 'jairc16'],
            [
                'name' => 'jairc16',
                'email' => 'jairc16@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('INFAMES ESPORT');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'INFAMES ESPORT',
            'color_primario' => '#000000',
            'color_secundario' => '#FF0000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $codt->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [27, 'PRENDELOYLS', 'DC'],
            [4, 'LUCHOPINELOO', 'DFC'],
            [5, 'freddyfeo', 'MC'],
            [8, 'DO_G4RRINCHA', 'MC'],
            [10, 'Alexis_doo', 'MCO'],
            [18, 'driiiler', 'MD'],
            [4, 'Kevinxd1904', 'MI'],
            [9, 'bySaam-', 'DC'],
            [11, 'Rapper-stone', 'DC'],
            [3, 'ZecaronixX', 'DFC'],
            [1, 'naokiuyehara', 'POR'],
            [14, 'jairc16', 'MI'],
            [5, 'Q-RAAY', 'DFC'],
            [7, 'GinoIT2000', 'MC'],
            [6, 'Shot-enzmigue17', 'MC'],
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
                'posicion' => strtoupper($posicion),
            ]);
        }
    }
}
