<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoJuventudAvanceSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-1-4-2']);

        $dt = User::firstOrCreate(
            ['id_ea' => 'iL_JOELINH0'],
            [
                'name' => 'iL_JOELINH0',
                'email' => 'il_joelinh0@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Juventud Avance eSports');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Juventud Avance eSports',
            'color_primario' => '#0000FF',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dt->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [1, 'ShiShibandio', 'Arquero', 'GK'],
            [21, 'zCamilex', 'Arquero', 'GK'],
            [17, 'ALL3XIISS_17', 'Defensa', 'DFC'],
            [37, 'rrrenatox', 'Defensa', 'DFC'],
            [77, 'its__koke17', 'Defensa', 'DFC'],
            [97, 'CSWC4M1L0', 'Defensa', 'DFC'],
            [5, 'Ramiresl5l', 'Mediocampista', 'MCD'],
            [10, 'Shelosscrazy', 'Mediocampista', 'MCO'],
            [18, 'vCarlomagno-', 'Mediocampista', 'MC'],
            [22, 'zlHudson-l7l', 'Mediocampista', 'MD'],
            [23, 'Neebvloss0', 'Mediocampista', 'MD'],
            [26, 'Gnsteramerican77', 'Mediocampista', 'MC'],
            [69, 'SinnnnNx', 'Mediocampista', 'MI'],
            [88, 'iL_JOELINH0', 'Mediocampista', 'MC'],
            [94, 'NnelozZ-', 'Mediocampista', 'MCO'],
            [7, 'Debatros_', 'Delantero', 'DC'],
            [90, 'vAlejandromagno-', 'Delantero', 'DC'],
            [93, 'Emperador_ZCRIS', 'Delantero', 'DC'],
            [99, 'M471_99_', 'Delantero', 'DC'],
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
