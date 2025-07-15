<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoExiliadosFCSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'PATOSKY'],
            [
                'name' => 'Tatan - Patosky',
                'email' => 'patosky@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Exiliados FC');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Exiliados FC',
            'color_primario' => '#FFFFFF',
            'color_secundario' => '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [11, 'jinchxriki12', 'DELANTERO', 'DC'],
            [9, 'ElDemiCharrua', 'DELANTERO', 'DC'],
            [12, 'Crownnpatofeo', 'DELANTERO', 'DC'],
            [17, 'Vendetta', 'DELANTERO', 'DC'],
            [19, 'StevenKRL', 'DELANTERO', 'ED'],
            [29, 'PATOSKY', 'DELANTERO', 'ED'],
            [7, 'Jxvy26', 'DELANTERO', 'EI'],
            [10, 'Taaataaan', 'MEDIO', 'MCO'],
            [23, 'iam_bastiaan', 'MEDIO', 'MC'],
            [6, 'FARC7x47', 'MEDIO', 'MC'],
            [20, 'DonDiegos', 'MEDIO', 'MCD'],
            [22, 'Chichadalton14', 'DEFENSA', 'LI'],
            [5, 'Chanejazu', 'DEFENSA', 'LD'],
            [4, 'LOCOSERES', 'DEFENSA', 'DFC'],
            [3, 'MORALESHECKS', 'DEFENSA', 'DFC'],
            [2, 'Janitooxx0215', 'DEFENSA', 'DFC'],
            [1, 'JafethMQz', 'ARQUERO', 'GK'],
            [25, 'zWOODY25', 'ARQUERO', 'GK'],
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
