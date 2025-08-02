<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoFcChanchitosSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-4-2']);

        $dt = User::firstOrCreate(
            ['id_ea' => 'joanvaras'],
            [
                'name' => 'Joan Varas',
                'email' => 'joanvaras@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('FC Chanchitos');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'FC Chanchitos',
            'color_primario' => '#444444',
            'color_secundario' => '#DDDDDD',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dt->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [51, 'joanvaras', 'volante'],
            [80, 'maranplay', 'mcd'],
            [41, 'TomaroGk', 'gk'],
            [19, 'Matiaslodbrok29', 'dc'],
            [42, 'Xchico_terry', 'volante'],
            [27, 'ALEMG365', 'defensa'],
            [17, 'Pacheco_xx', 'defensa'],
            [10, 'Leoflai', 'delantero'],
            [49, 'dopaminameh', 'defensa'],
            [13, 'CNeira25', 'defensa'],
            [37, 'Robertbriand', 'volante'],
            [18, 'Rsolar_xsc', 'defensa'],
            [9, 'marceloav_', 'dc'],
            [20, 'JotaceeCL', 'medio campo'],
            [29, 'BSG-Edutemplar', 'volante'],
            [19, 'zSarnotx_', 'gk'],
        ];

        $mapPos = [
            'gk' => 'POR',
            'portero' => 'POR',
            'defensa' => 'DFC',
            'volante' => 'MI',
            'medio campo' => 'MC',
            'mcd' => 'MCD',
            'mc' => 'MC',
            'delantero' => 'DC',
            'dc' => 'DC',
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
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
                'posicion' => $mapPos[strtolower($posicion)] ?? 'MC',
            ]);
        }
    }
}
