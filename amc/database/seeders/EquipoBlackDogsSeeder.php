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
        $formacionNombre = '3-5-2';
        $formacion = Formacion::firstOrCreate(['nombre' => $formacionNombre]);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'RagnarRock77', 'email' => 'ragnarrock77@mail.com'],
            [
                'name' => 'RagnarRock77',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

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

        // Mapeo posición -> rol
        $etiquetas = [
            'PO' => 'Portero',
            'DFC' => 'Defensa',
            'DFD' => 'Defensa',
            'DFI' => 'Defensa',
            'LD' => 'Defensa',
            'LI' => 'Defensa',
            'MCD' => 'Volante',
            'MDD' => 'Volante',
            'MDI' => 'Volante',
            'MC' => 'Volante',
            'MCI' => 'Volante',
            'MCR' => 'Volante',
            'MD' => 'Volante',
            'MI' => 'Volante',
            'MCO' => 'Volante',
            'MOI' => 'Volante',
            'MOD' => 'Volante',
            'EI' => 'Delantero',
            'ED' => 'Delantero',
            'DI' => 'Delantero',
            'DD' => 'Delantero',
            'DC' => 'Delantero',
            'SDD' => 'Delantero',
            'SDI' => 'Delantero',
            'MP' => 'Delantero',
        ];

        $formacionesConPosiciones = [
            "3-5-2" => ["PO", "DFI", "DFC", "DFD", "MCD", "MI", "MCO", "MD", "MC", "DI", "DD"],
        ];

        $posicionesRequeridas = $formacionesConPosiciones[$formacionNombre];
        $titularesAsignados = [];

        $jugadores = [
            [25, 'kokeLove',         'PO',  'Chile'],
            [4,  'GarroshHllscrm',   'DFI', 'Bolivia'],
            [77, 'QER-Heres',        'DFC', 'Chile'],
            [3,  'Sisteam77',        'DFD', 'Chile'],
            [66, 'Autentick666',     'MCD', 'Chile'],
            [36, 'Bakings',          'MI',  'Chile'],
            [11, 'xPACHEKING-l11l',  'MCO', 'Chile'],
            [88, 'mvtiass77',        'MD',  'Chile'],
            [5,  'Koke-_-nasri8',    'MC',  'Chile'],
            [10, 'Cr0wndemon',       'DI',  'Chile'],
            [8,  'RagnarRock77',     'DD',  'Chile'],
            [6,  'Darthnacho',       'DC',  'Chile'],
            [9,  'Aka_Crisonell',    'DC',  'Chile'],
            [10, 'Raikus22',         'DFC', 'Chile'],
            [70, 'Dangers237',       'DFC', 'Chile'],
            [99, 'FyLe_x_Tomas',     'PO',  'Chile'],
            [35, 'Tobal_hiphop',     'MC',  'Chile'],
            [34, 'Demons_King14',    'MCO', 'Chile'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion, $nacionalidad]) {
            $posicion = strtoupper($posicion);
            $id_ea = trim($id_ea);
            if (!$id_ea || !isset($etiquetas[$posicion])) continue;

            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea, 'email' => $emailify($id_ea)],
                [
                    'name' => $id_ea,
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            // Titular si su posición es requerida y no fue cubierta aún
            $titular = false;
            if (in_array($posicion, $posicionesRequeridas) && !in_array($posicion, $titularesAsignados)) {
                $titular = true;
                $titularesAsignados[] = $posicion;
            }

            Plantilla::firstOrCreate([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
            ], [
                'numero' => $numero,
                'rol' => $etiquetas[$posicion],
                'posicion' => $posicion,
                'titular' => $titular,
            ]);
        }
    }
}
