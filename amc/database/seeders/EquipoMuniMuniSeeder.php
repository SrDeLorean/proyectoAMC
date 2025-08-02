<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoMuniMuniSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Yoeel Cangri'],
            [
                'name' => 'Yoeel Cangri',
                'email' => 'yoeelcangri@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = $dueno;

        $logoNombre = strtolower("Muni Muni eSports");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'azul' => '#0000FF',
            'amarillo' => '#FFFF00',
            // otros colores si necesitas
        ];

        $colorPrimario = 'azul';
        $colorSecundario = 'amarillo';

        $equipo = Equipo::create([
            'nombre' => 'Muni Muni eSports',
            'color_primario' => $coloresHex[strtolower($colorPrimario)] ?? '#000000',
            'color_secundario' => $coloresHex[strtolower($colorSecundario)] ?? '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [10, 'Yoeel Cangri', 'Mediocampista', 'MC'],
            [null, 'ITzRobToxiiC', 'Delantero', 'ED'],
            [9, 'Antonioxs08', 'Delantero', 'DC'],
            [23, 'Jking24k', 'Mediocampista', 'MCD'],
            [19, 'MatiKing02', 'Delantero', 'ED'],
            [14, 'arturomatus', 'Delantero', 'DC'],
            [96, 'ShiShi', 'Defensa', 'DFD'],
            [95, 'chrisvagg24', 'Defensa', 'DFI'],
            [11, 'Leomachadox4', 'Delantero', 'EI'],
            [5, 'Yujiro Hanma543', 'Defensa', 'DFC'],
            [11, 'Xoooakin', 'Medio', 'MC'],
            [3, 'Mauro85', 'Defensa', 'DFC'],
            [1, 'Sgonzalez23', 'Portero', 'POR'],
            [32, 'EfflyJashin', 'Portero', 'POR'],
        ];

        $numeroDefault = 100;
        foreach ($jugadores as [$numero, $id_ea, $rol, $posicion]) {
            if (empty($id_ea)) continue;

            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => strtolower(preg_replace('/[^a-z0-9]/i', '', $id_ea)) . '@mail.com',
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero ?? $numeroDefault++,
                'rol' => strtolower($rol),
                'posicion' => strtoupper($posicion),
            ]);
        }
    }
}
