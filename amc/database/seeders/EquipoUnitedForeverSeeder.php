<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoUnitedForeverSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']); // Ajusta si tienes formación específica

        $dueno = User::firstOrCreate(
            ['id_ea' => 'vCodesssj'],
            [
                'name' => 'vCodesssj',
                'email' => 'vcodesssj@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('UnitedForever');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'United Forever',
            'color_primario' => '#0000FF', // Puedes ajustar los colores si quieres
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [13, 'vCodesssj', 'MCO', 'Chile'],
            [78, 'ZAMORA_CDLS_', 'DFC', 'Chile'],
            [7, 'Esteban_123123', 'DC', 'Chile'],
            [23, 'EL_DEIIVID_', 'DFC', 'Chile'],
            [14, 'JeanVillar_14', 'EXT', 'Chile'],
            [2, '1Belrazz', 'LAT', 'Chile'],
            [6, 'RodoKing_06', 'DFC', 'Chile'],
            [8, 'CutiiRomero', 'DFC', 'Chile'],
            [1, 'Gallardo_2501', 'GK', 'Chile'],
            [23, 'Darcechoke7', 'EXT', 'Chile'],
            [21, 'PatrickMora21', 'LAT', 'Chile'],
            [69, 'Jinch6969', 'MCD', 'Chile'],
            [0, 'zTyzesx', 'GK', 'Chile'],
            [21, 'Yooelm7', 'LAT', 'Chile'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion, $nacionalidad]) {
            if (!$id_ea) continue;

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
