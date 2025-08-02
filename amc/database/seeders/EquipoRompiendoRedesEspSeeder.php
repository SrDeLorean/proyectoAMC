<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoRompiendoRedesEspSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Fabicornejo7'],
            [
                'name' => 'Fabicornejo7',
                'email' => 'fabicornejo7@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Rompiendo Redes Esp');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Rompiendo Redes Esp',
            'color_primario' => '#0000FF', // Azul
            'color_secundario' => '#FFFF00', // Amarillo
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [87, 'igxnacii0DM', 'ARQUERO', 'PO'],
            [5, 'FabiCornejo7', 'DEFENSA', 'DFC'],
            [10, 'Kidox96', 'MEDIOCAMPISTA', 'MCO'],
            [0, 'zEtihen_', 'VOLANTE', 'VOL'],
            [0, 'Rodrigeo21', 'DEFENSA', 'DFC'],
            [0, 'LucasRubard07', 'DEFENSA', 'DFC'],
            [0, 'J_RomoV', 'MEDIOCAMPISTA', 'MC'],
            [0, 'Pancho_tf1', 'DELANTERO', 'DC'],
            [0, 'Suupraa14', 'VOLANTE', 'MC'],
            [0, 'Robertillo_10', 'DEFENSA', 'LD'],
            [0, 'Elwaton10', 'MEDIOCAMPISTA', 'MCD'],
            [0, 'Koke-.-Nasri', 'DELANTERO', 'VOL'],
            [0, 'Cachorringss', 'DEFENSA', 'LI'],
            [0, 'zJoCkEr-l22l', 'ARQUERO', 'PO'],
            [0, 'JanoYKpz', 'ARQUERO', 'PO'],
            [0, 'xXDevas0811Xx', 'DELANTERO', 'DC'],
            [0, 'X_yanZ_X', 'VOLANTE', 'LATERAL'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion, $pos_especifica]) {
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
                'posicion' => strtoupper(str_replace([' ', '-', '.'], '', $pos_especifica ?: $posicion)),
            ]);
        }
    }
}
