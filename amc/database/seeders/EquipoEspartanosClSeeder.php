<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoEspartanosClSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-1-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'matacl'],
            [
                'name' => 'matacl',
                'email' => 'matacl@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("Espartanos CL");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Espartanos CL',
            'color_primario' => '#FF0000',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id, // como no hay entrenador
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [9, 'matacl', 'Delantero', 'DC'],
            [12, 'gallegosy315', 'Lateral', 'DFI'],
            [8, 'filiano_23', 'Lateral', 'DFD'],
            [69, 'manchancl', 'Defensa', 'DFC'],
            [10, 'ix--starxz--xi', 'Delantero', 'DC'],
            [4, 'wcpereira', 'Defensa', 'DFC'],
            [7, 'zKingAzul_cl', 'Mediocampista', 'MCO'],
            [89, 'D4n11t00', 'Mediocampo', 'MI'],
            [18, 'Chascon', 'Delantero', 'DC'],
            [66, 'Vicent93lml', 'Defensa', 'MCD'],
            [11, 'tutty_magno', 'Delantero', 'DC'],
            [14, 'Xileno-74', 'Mediocampo', 'MI'],
            [85, 'JoaSaintUy', 'Mediocampo', 'MC'],
            [6, 'dackisback', 'Mediocampo', 'MC'],
            [92, 'Neva-7770', 'Portero', 'POR'],
            [14, 'fixa_lk', 'Lateral', 'DFD'],
        ];

        $emailGenerator = fn($id_ea) =>
            strtolower(preg_replace('/[^a-z0-9]/i', '', $id_ea)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $rol, $posicion]) {
            if (empty($id_ea)) continue;

            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => $emailGenerator($id_ea),
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => strtoupper($posicion),
            ]);
        }
    }
}
