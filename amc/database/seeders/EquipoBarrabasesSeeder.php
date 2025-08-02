<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoBarrabasesSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'BRB_COYOTE_l10l', 'email' => 'brb_coyote_l10l@mail.com'],
            [
                'name' => 'BRB_COYOTE_l10l',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'BRB_NUNI_I33I', 'email' => 'brb_nuni_i33i@mail.com'],
            [
                'name' => 'BRB_NUNI_I33I',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Barrabases');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::firstOrCreate([
            'nombre' => 'Barrabases eSports',
            'id_usuario' => $dueno->id,
        ], [
            'color_primario' => '#FF0000', // rojo
            'color_secundario' => '#000000', // negro/blanco combinable luego
            'id_formacion' => $formacion->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [66, 'BRB_NUNI_I33I', 'Defensa', 'Chile'],
            [22, 'BRB_Kako_l22l', 'Volante', 'Chile'],
            [14, 'BRB_NANO_l14l', 'Delantero', 'Chile'],
            [10, 'BRB_COYOTE_l10l', 'Volante', 'Chile'],
            [77, 'FlCHlTA77', 'Defensa', 'Chile'],
            [8, 'Acertijo_8_', 'MCD', 'Chile'],
            [9, 'Bastiii9', 'Delantero', 'Chile'],
            [7, 'xL_YoungCARI', 'Volante', 'Chile'],
            [88, 'CarloosAndres', 'Defensa', 'Chile'],
            [11, 'BRB_FOFI_11', 'Delantero', 'Irán'],
            [24, 'I-DemonPitbull-I', 'Defensa', 'Chile'],
            [20, 'Z4Z4I20I', 'Mediocampista', 'Chile'],
            [25, 'MemoAzul25', 'Portero', 'Chile'],
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
