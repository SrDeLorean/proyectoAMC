<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoSuccessorsSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Srdelorean'],
            [
                'name' => 'SrDeLorean',
                'email' => 'srdelorean@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'Jonchiko9309'],
            [
                'name' => 'Jonchiko',
                'email' => 'jonchiko@mail.com',
                'password' => bcrypt('password'),
                'role' => 'entrenador',
                'foto' => 'images/users/default-user.png',
            ]
        );

        $logoNombre = strtolower("Successors");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'azul' => '#0000FF',
            'negro' => '#000000',
        ];

        $colorPrimario = 'azul';
        $colorSecundario = 'Negro';

        $equipo = Equipo::create([
            'nombre' => 'Successors',
            'color_primario' => $coloresHex[strtolower($colorPrimario)] ?? '#000000',
            'color_secundario' => $coloresHex[strtolower($colorSecundario)] ?? '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [1, 'Kenji-Requidem', 'Portero', 'PO'],
            [69, 'Hacele1', 'Defensa', 'DFD'],
            [22, 'Cazueela', 'Defensa', 'DFC'],
            [44, 'Cl_Crow_Cl', 'Defensa', 'DFI'],
            [99, 'JorgitoDeus', 'Mediocampista', 'MD'],
            [14, 'Ezf14k', 'Mediocampista', 'MI'],
            [5, 'Chonpapii', 'Mediocampista', 'MCD'],
            [8, 'YayaaToure', 'Mediocampista', 'MCI'],
            [27, 'Jonchiko9309', 'Mediocampista', 'MCR'],
            [10, 'Srdelorean', 'Delantero', 'DI'],
            [19, 'Sepu_19', 'Delantero', 'DD'],

            // Suplentes (sin posición exacta en formación)
            [9, 'Tommy_Shelby', 'Delantero', 'DD'],
            [91, 'Danger91', 'Defensa', 'DFD'],
            [32, 'Ludn1ck', 'Mediocampista', 'MCD'],
            [4, 'tiomayas', 'Mediocampista', 'MI'],
            [65, 'mati_uru', 'Portero', 'PO'],
            [17, 'acicalado', 'Mediocampista', 'MD'],
        ];

        foreach ($jugadores as [$numero, $id_ea, $rol, $posicion]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                ['name' => $id_ea, 'email' => $id_ea . '@mail.com', 'password' => bcrypt('password')],
            );

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => $rol,
                'posicion' => $posicion,
            ]);
        }
    }
}
