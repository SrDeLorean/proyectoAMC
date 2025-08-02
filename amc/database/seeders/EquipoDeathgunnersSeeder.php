<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoDeathgunnersSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'DG2MILYT'],
            [
                'name' => 'DG2MILYT',
                'email' => 'dg2milyt@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador2 = User::firstOrCreate(
            ['id_ea' => 'Valdes I20I'],
            [
                'name' => 'Valdes I20I',
                'email' => 'valdesi20i@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("Deathgunners FC");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Deathgunners FC',
            'color_primario' => '#333333',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador2->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [19, 'ElSarnota_', 'Portero'],
            [77, 'sergiobenito1982', 'Portero'],
            [2, 'DG2MILYT', 'Defensa'],
            [32, 'Holly205', 'Defensa'],
            [23, 'Anti_gol', 'Defensa'],
            [90, 'GiovaCrrcs_L2R2', 'Defensa'],
            [3, 'W4t0nc4n06557', 'Defensa'],
            [27, 'Aldoningg', 'Medio Campista'],
            [92, 'DJSidrek-Cl', 'Medio Campista'],
            [99, 'NRG_Cloozz', 'Medio Campista'],
            [20, 'ShinoPls', 'Medio Campista'],
            [22, 'carloslob05', 'Volante'],
            [7, 'Valdes I20I', 'Volante'],
            [98, 'Yor211198 I20I', 'Delantero'],
            [14, 'mts_flrss14', 'Delantero'],
            [17, 'xMcFlay17', 'Delantero'],
            [9, 'JoaqueenCl', 'Delantero'],
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

            $posMap = [
                'portero' => 'POR',
                'defensa' => 'DFC',
                'medio campista' => 'MC',
                'volante' => 'MI',
                'delantero' => 'DC',
            ];

            Plantilla::firstOrCreate([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => $posMap[strtolower($posicion)] ?? 'MC',
            ]);
        }
    }
}
