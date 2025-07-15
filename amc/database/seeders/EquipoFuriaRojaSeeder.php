<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoFuriaRojaSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-2-2-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'amadizmat'],
            [
                'name' => 'amadizmat',
                'email' => 'amadizmat@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'Topnacho1109'],
            [
                'name' => 'Topnacho1109',
                'email' => 'topnacho1109@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Furia Roja');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Furia Roja',
            'color_primario' => '#FF0000',
            'color_secundario' => '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [17, 'Topnacho1109', 'defensa', 'DFC'],
            [56, 'amadizmat', 'defensa', 'LD'],
            [9, 'YKz CAYNHO 9', 'delantero', 'DC'],
            [1, 'Alex29PunkFr', 'portero', 'POR'],
            [11, 'SimónSilvaSW', 'volante', 'MCO'],
            [12, 'OSCARTORO42', 'delantero', 'DC'],
            [33, 'feliandmax2020', 'delantero', 'DC'],
            [23, 'tyrannic98', 'mediocampista', 'MCD'],
            [16, 'BenjakingFts', 'defensa', 'DFC'],
            [15, 'sebinho', 'volante', 'MCO'],
            [4, 'EmJeyO', 'defensa', 'LD'],
            [20, 'jadiehd_m17', 'volante', 'MCO'],
            [7, 'Debatros_', 'delantero', 'DC'],
            [89, 'brokyclown', 'defensa', 'LI'],
            [69, 'Sheloz69', 'defensa', 'LD'],
            [37, 'LGNHector', 'defensa', 'DFC'],
            [22, 'Janozky', 'defensa', 'LD'],
            [5, 'matiazl1', 'volante', 'MCO'],
            [13, 'VivarFR13', 'centrocampista', 'MCD'],
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
