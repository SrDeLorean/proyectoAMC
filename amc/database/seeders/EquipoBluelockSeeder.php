<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoBluelockSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']); // Puedes ajustar si tienen otra fija

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Maudrickk'],
            [
                'name' => 'Maudrickk',
                'email' => 'maudrickk@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Bluelock');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Bluelock',
            'color_primario' => '#0000FF',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [55, 'vichoLDA23', 'DFC'],
            [10, 'Maudrickk', 'DC'],
            [11, 'Perrin9999', 'MD'],
            [70, 'eleuceaese_efe', 'GK'],
            [35, 'RR-ilpadrino', 'MCD'],
            [24, 'Kamilo__24', 'DC'],
            [99, 'Jocajocasalas', 'DFC'],
            [77, 'dieguiinho0_', 'MI'],
            [2, 'DarkZefhiroth', 'DFC'],
            [66, 'Thebastiandc12', 'MCO'],
            [21, 'P1p3-94', 'DFC'],
            [7, 'SAC_kirito_', 'DC'],
            [1, 'WizTaylo0r', 'GK'],
            [27, 'xBarbaroStyLeV8', 'DFC'],
            [8, 'spider-gaspar', 'MCI'],
            [1, 'zarnota', 'GK'],
            [8, 'das_underground', 'MI'],
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
