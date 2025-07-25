<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoBasofiasSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'elmagoch'],
            [
                'name' => 'Elmagoch',
                'email' => 'elmagoch@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("Basofias FC");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Basofias FC',
            'color_primario' => '#333333',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [31, 'Glaikit_kiddo', 'Portero'],
            [10, 'elmagoch', 'Mediocampista'],
            [24, 'BobyzZaW', 'Delantero'],
            [19, 'fuego_meminho', 'Delantero'],
            [69, 'Huenchinho', 'Mediocampista'],
            [33, 'agrepschl', 'Defensa'],
            [45, 'BENJAREAD11', 'Defensa'],
            [14, 'oglvl', 'Defensa'],
            [66, 'verdura_usada3', 'Mediocampista'],
            [4, 'killme_08', 'Defensa'],
            [7, 'herly1233103', 'Volante'],
            [42, 'xElPipazo', 'Defensa'],
            [11, 'SirNico', 'Defensa'],
            [7, 'DanielJB23', 'Mediocampista'],
        ];

        $numeroAuto = 100;
        foreach ($jugadores as [$numero, $id_ea, $rol]) {
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

            $posicion = match (strtolower($rol)) {
                'portero' => 'POR',
                'defensa' => 'DFC',
                'mediocampista', 'volante' => 'MC',
                'delantero' => 'DC',
                default => 'MC',
            };

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero ?? $numeroAuto++,
                'rol' => 'jugador',
                'posicion' => $posicion,
            ]);
        }
    }
}
