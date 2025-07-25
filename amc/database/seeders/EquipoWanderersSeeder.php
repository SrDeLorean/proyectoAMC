<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoWanderersSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dt = User::firstOrCreate(
            ['id_ea' => 'LMF_GoldenBoyMvP'],
            [
                'name' => 'LMF_GoldenBoyMvP',
                'email' => 'lmfgoldenboymvp@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("Wanderers Esports");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Wanderers Esports',
            'color_primario' => '#008000',
            'color_secundario' => '#FFFFFF',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dt->id,
            'id_usuario2' => $dt->id,
            'logo' => "images/equipos/{$logoNombre}.png",
        ]);

        $jugadores = [
            [27, 'MaickyBP', 'defensa', 'DFC'],
            [33, 'Pedro_GB31', 'defensa', 'DFC'],
            [5, 'Fran_Full', 'medio campista', 'MC'],
            [8, 'Nvvxiito', 'medio campista', 'MC'],
            [10, 'lMxtrix-', 'medio campista', 'MCO'],
            [16, 'LMF_GoldenBoyMvP', 'delantero', 'ED'],
            [4, 'DiegoLTS', 'volante', 'MI'],
            [15, 'BiG_Doogg15', 'defensa', 'DFC'],
            [20, 'Medelinho_', 'defensa', 'LD'],
            [47, 'JcNr_9', 'delantero', 'EI'],
            [77, 'Rodriguinhoo77', 'delantero', 'DC'],
            [18, 'Fxbixn_m', 'defensa', 'DFC'],
            [19, 'Faze_Maty20', 'delantero', 'ED'],
            [14, 'Mati_ce97', 'medio campista', 'MC'],
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
