<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoMadnessSeeder extends Seeder
{
    public function run(): void
    {
        // Formación por defecto: 4-3-3
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Dreckab'],
            [
                'name' => 'Dreckab',
                'email' => 'dreckab@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Madness');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Madness',
            'color_primario' => '#222222',
            'color_secundario' => '#999999',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [26, 'Ntworkz', 'POR'],
            [27, 'VitocoMix2376', 'DFC'],
            [66, 'Dreckab', 'DFC'], // ← dueño también está como jugador
            [8, 'Lokotron_GB', 'DFC'],
            [97, 'churrinho10', 'DFC'],
            [29, 'zFraanS__', 'MC'],
            [88, 'AntoiineCr7', 'MC'],
            [10, 'Erick2010Live', 'MC'],
            [77, 'Maxfoller9', 'MC'],
            [26, 'NEROOXT', 'MC'],
            [2, 'Chaetumare', 'DC'],
            [7, 'TurritoElJefe', 'DC'],
            [9, 'D_apvrrvguez9', 'DC'],
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
