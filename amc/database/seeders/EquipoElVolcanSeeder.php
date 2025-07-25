<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoElVolcanSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'munozjosee'],
            [
                'name' => 'munozjosee',
                'email' => 'munozjosee@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'rm_boris12'],
            [
                'name' => 'rm_boris12',
                'email' => 'rmboris12@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("El Volcán FC");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'El Volcán FC',
            'color_primario' => '#00BFFF', // celeste
            'color_secundario' => '#FF0000', // rojo
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [33, 'munozjosee', 'defensa', 'DFC'],
            [1, 'YvngAlonso', 'arquero', 'GK'],
            [35, 'rm_boris12', 'defensa', 'DFC'],
            [69, 'franklin_bolo', 'volante', 'MI'],
            [30, 'JAVIP_ELPRO2008', 'volante', 'MD'],
            [26, 'Leandro98', 'volante', 'MC'],
            [80, 'brunoodrizzy', 'delantero', 'DC'],
            [5, 'Kr_23k', 'defensa', 'DFC'],
            [9, 'Andyskills', 'delantero', 'DC'],
            [17, 'Pumped_Vi_Brando', 'volante', 'MC'],
            [99, 'MaxCruzado', 'arquero', 'GK'],
            [19, 'f4und3zz19', 'delantero', 'DC'],
            [55, 'deadxino', 'defensa', 'DFI'],
            [77, 'Lucapi1', 'mediocampista', 'MCO'],
            [16, 'Groncho83', 'mediocampista', 'MI'],
            [87, 'Miguelon8717', 'mediocampista', 'MCO'],
            [14, 'Nikogb06', 'mediocampista', 'MD'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $rol, $posicion]) {
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

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => strtoupper($posicion),
            ]);
        }
    }
}
