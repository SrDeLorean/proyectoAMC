<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoTorosSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Sgo_pro15', 'email' => 'sgo_pro15@mail.com'],
            [
                'name' => 'Sgo_pro15',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'MaurixD_gb', 'email' => 'maurixd_gb@mail.com'],
            [
                'name' => 'MaurixD_gb',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Toros Fc');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::firstOrCreate([
            'nombre' => 'Toros Fc',
            'id_usuario' => $dueno->id,
        ], [
            'color_primario' => '#FF0000', // rojo
            'color_secundario' => '#FFFFFF', // blanco
            'id_formacion' => $formacion->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [14, 'Sgo_pro15', 'MCO,MC,DFC,EI', 'Chile'],
            [21, 'brunnoo221', 'DC,MCO', 'Argentina'],
            [73, 'Matito_black22', 'LD,LI,MI,MC,MD', 'Chile'],
            [10, 'XxsamuraixX26', 'DC', 'Argentina'],
            [3, 'MaurixD_gb', 'DFC', 'Chile'],
            [22, 'brunito_2208', 'DC', 'Chile'],
            [8, 'Benjarm9040', 'MEDIOCAMPO', 'Chile'],
            [18, 'TheJorn1', 'VOLANTE', 'Chile'],
            [99, 'chino99cl', 'MCD', 'Chile'],
            [17, 'josue_2punto0', 'ED,EI,DC,DFC', 'Chile'],
            [1, 'maartin_p', 'GK', 'Chile'],
            [69, 'DonTatani', 'DELANTERO', 'Chile'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posiciones, $nacionalidad]) {
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
                'posicion' => strtoupper(str_replace([' ', '-'], '', $posiciones)),
            ]);
        }
    }
}
