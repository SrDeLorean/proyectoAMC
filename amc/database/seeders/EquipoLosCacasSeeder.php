<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoLosCacasSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos una formación genérica, puedes ajustarla después
        $formacion = Formacion::firstOrCreate(['nombre' => '4-4-2']);

        // Crear un usuario "dueño" ficticio
        $dueno = User::firstOrCreate(
            ['id_ea' => 'mataawuela'],
            [
                'name' => 'mataawuela',
                'email' => 'mataawuela@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Los Cacas');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Los Cacas',
            'color_primario' => '#444444', // gris oscuro por defecto
            'color_secundario' => '#AAAAAA', // gris claro por defecto
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [1, 'mataawuela', 'POR'],
            [2, 'OFFSPR1NG', 'DFC'],
            [34, 'TOX-muralla', 'DFC'],
            [69, 'NicoMasterHD', 'LD'],
            [4, 'chrisep11', 'LI'],
            [92, 'Eduxd11', 'MC'],
            [20, 'Leon103850', 'MC'],
            [74, 'elcorrea4', 'MI'], // tomado MI como principal
            [7, 'Kevin_lda_cl', 'ED'],
            [19, 'yermid_29', 'DC'],
            [10, 'Diccappo', 'DC'],
            [79, 'Iro79', 'MCD'],
            [17, 'dragonseba05', 'DC'], // corrigió rol delantero, posición DFC no coherente con delantero, lo asigno DC
            [31, 'nones031', 'MC'], // múltiples posiciones, se eligió MC por centralidad
            [15, 'Dialonso10', 'DFC'], // múltiples posiciones, se prioriza defensa central
            [72, 'LPG_BAZAN', 'MD'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion]) {
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
