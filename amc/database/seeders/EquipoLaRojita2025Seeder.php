<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoLaRojita2025Seeder extends Seeder
{
    public function run(): void
    {
        // Formación por defecto, ajustar si hay formación exacta
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'SrBasten'],
            [
                'name' => 'SrBasten',
                'email' => 'srbasten@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('LaRojita2025');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'LaRojita2025',
            'color_primario' => '#FF0000', // rojo
            'color_secundario' => '#FFFFFF', // blanco
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [1, 'SH1LO', 'GK'],
            [77, 'rorrito2603', 'DFC'],
            [3, 'Axnbeto', 'DFC'],
            [6, 'TheJokerSampis', 'DFC'],
            [4, 'Andynho9054', 'DFC'],
            [78, 'ELFIGURA', 'MCD'],
            [10, 'SIR_BASTEN', 'MCO'],
            [5, 'B_LEmperor', 'MCD'],
            [8, 'ChoroTapsin', 'MCD'],
            [15, 'El_Majlc0', 'MCD'],
            [23, 'basty_gb_32', 'MCD'],
            [11, 'yovngkiingsss', 'VOLANTE'],
            [17, 'whoiisn4huel', 'VOLANTE'],
            [21, 'FValdo', 'LD'],
            [74, 'manolomanuel74', 'DC'],
            [33, 'benja-chilee', 'LI'],
            [52, 'Rubioyarce', 'MCD'],
            [44, 'Sweyzy_7', 'DC'],
            [19, 'LewandowCris_19', 'LD'],
            [9, 'SparklyHair43', 'DC'],
            [18, 'theyacoo', 'DC'],
            [20, 'Yeash-29', 'DC'],
            [65, 'kronos2532', 'DC'],
            [7, 'CR7_RonaldooSiuu', 'DC'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion]) {
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
