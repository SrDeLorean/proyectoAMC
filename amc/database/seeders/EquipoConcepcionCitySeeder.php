<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoConcepcionCitySeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'LeoVera1990'],
            [
                'name' => 'Leonardo Vera Arias',
                'email' => 'leovera1990@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = $dueno;

        $logoNombre = strtolower("Concepción City Club eSports");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'negro' => '#000000',
            'celeste' => '#00BFFF',
            'blanco' => '#FFFFFF',
            // otros colores si se usan
        ];

        $colorPrimario = $coloresHex['negro']; // base negra
        $colorSecundario = $coloresHex['celeste'] ?? '#00BFFF'; // celeste de apoyo

        $equipo = Equipo::create([
            'nombre' => 'Concepción City Club eSports',
            'color_primario' => $colorPrimario,
            'color_secundario' => $colorSecundario,
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [92, 'Ldaerik11', 'Defensa'],
            [13, 'LeoVera1990', 'Defensa'],
            [69, 'Nino407GR', 'Mediocampista'],
            [16, 'DiegMd3', 'Mediocampista'],
            [42, 'Ivanobski', 'Mediocampista'],
            [28, 'Tonioo28GB', 'Defensa'],
            [7, 'crizz_barbercl', 'Delantero'],
            [2, 'naxocruzado999', 'Delantero'],
            [14, 'felipeworship', 'Mediocampista'],
            [30, 'Sr_Yorichy', 'Defensa'], // ajustado 'por la derecha'
            [22, 'ryo7a-Miyagi_', 'Delantero'],
            [11, 'RomeoZR15', 'Defensa'],
            [21, 'Jonnyscott666', 'Mediocampista'],
            [10, 'felipelionel_', 'Mediocampista'],
            [70, 'Zerinhobest70', 'Mediocampista'],
            [1, 'stiffgiuga', 'Portero'],
            [32, 'pietrok32', 'Delantero'],
            [20, 'RFCriss', 'Mediocampista'],
            [99, 'Daoles9', 'Mediocampista'],
        ];

        foreach ($jugadores as [$numero, $id_ea, $rol]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => strtolower(str_replace(['|', ' ', '__'], '', $id_ea)) . '@mail.com',
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            $posicion = match (strtolower($rol)) {
                'portero' => 'POR',
                'defensa' => 'DFC',
                'mediocampista' => 'MC',
                'delantero' => 'DC',
                default => 'MC',
            };

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => $posicion,
            ]);
        }
    }
}
