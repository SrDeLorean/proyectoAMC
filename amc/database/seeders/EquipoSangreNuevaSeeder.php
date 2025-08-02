<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoSangreNuevaSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Caxorro16'],
            [
                'name' => 'Caxorro16',
                'email' => 'caxorro16@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Sangre Nueva');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Sangre Nueva',
            'color_primario' => '#8B0000', // rojo oscuro sangre
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [96, 'Caxorro16', 'DC'],
            [27, 'UnselfishTulip8', 'POR'],
            [21, 'james212199', 'MC'],
            [11, 'fernandouc859', 'DC'],
            [10, 'BleakMaple5', 'DC'],
            [5, 'Fernando9098', 'DFC'],
            [14, 'manu_araneda1', 'MC'],
            [13, 'Shockwan5539', 'MC'],
            [4, 'Critical___420', 'DFC'],
            [11, 'M0tiva0', 'DC'],
            [32, 'LukaasGio', 'MC'],
            [69, 'Autentick', 'MC'],
            [25, 'BastianMaicol', 'MC'],
            [23, 'tullaloka65', 'MC'],
            [6, 'Dreamlisboa', 'MCD'],
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
