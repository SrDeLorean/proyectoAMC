<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoBlackDogsSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'RagnarRock77', 'email' => 'ragnarrock77@mail.com'],
            [
                'name' => 'RagnarRock77',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('BlackDogs');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::firstOrCreate([
            'nombre' => 'BlackDog',
            'id_usuario' => $dueno->id,
        ], [
            'color_primario' => '#000000',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [8, 'RagnarRock77', 'Delantero', 'Chile'],
            [25, 'kokeLove', 'Portero', 'Chile'],
            [10, 'Cr0wndemon', 'Extremo', 'Chile'],
            [4, 'GarroshHllscrm', 'Defensa', 'Bolivia'],
            [5, 'Koke-_-nasri8', 'Volante', 'Chile'],
            [6, 'Darthnacho', 'Delantero', 'Chile'],
            [11, 'xPACHEKING-l11l', 'Medio Campista', 'Chile'],
            [9, 'Aka_Crisonell', 'Delantero', 'Chile'],
            [77, 'QER-Heres', 'Defensa', 'Chile'],
            [10, 'Raikus22', 'Defensa', 'Chile'],
            [70, 'Dangers237', 'Defensa', 'Chile'],
            [66, 'Autentick666', 'Mediocampo', 'Chile'],
            [88, 'mvtiass77', 'Volante', 'Chile'],
            [36, 'Bakings', 'Volante', 'Chile'],
            [99, 'FyLe_x_Tomas', 'Arquero', 'Chile'],
            [3, 'Sisteam77', 'Defensa', 'Chile'],
            [35, 'Tobal_hiphop', 'Mediocampo', 'Chile'],
            [34, 'Demons_King14', 'Extremo', 'Chile'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion, $nacionalidad]) {
            if (!$id_ea) continue;

            $jugador = User::firstOrCreate(
                ['id_ea' => trim($id_ea), 'email' => $emailify($id_ea)],
                [
                    'name' => trim($id_ea),
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            Plantilla::firstOrCreate([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
            ], [
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => strtoupper(str_replace([' ', '-'], '', $posicion)),
            ]);
        }
    }
}
