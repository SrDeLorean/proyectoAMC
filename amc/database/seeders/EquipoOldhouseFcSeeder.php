<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoOldhouseFcSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']); // Por defecto, asumo 3-5-2, puedes cambiar si quieres

        $dueno = User::firstOrCreate(
            ['id_ea' => 'MaTaa_Viral'],
            [
                'name' => 'MaTaa_Viral',
                'email' => 'mataa_viral@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Oldhouse FC');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Oldhouse FC',
            'color_primario' => '#800080', // Morado en hex
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [11, 'MaTaa_Viral', 'DC'],
            [21, 'zenoruel', 'ED'],
            [39, 'lcgavilan97', 'DFC'],
            [80, 'AsuraCHL', 'POR'],
            [6, 'Hunt3rkiller', 'MC'],
            [62, 'Nawel99', 'MCO'],
            [1, 'Piriwon', 'MC'],
            [5, 'Currysco', 'DC'],
            [13, 'BaadLife', 'DC'],
            [17, 'SSolaSS', 'EI'],
            [99, 'Sebaaa_kl', 'DFC'],
            [72, 'Maxi_Reyna', 'DFC'],
            [9, 'AyakaArai', 'DFC'],
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
