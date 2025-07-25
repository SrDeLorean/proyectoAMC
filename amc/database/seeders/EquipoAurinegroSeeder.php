<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoAurinegroSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '3-5-2']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'markramos26'],
            [
                'name' => 'markramos26',
                'email' => 'markramos26@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $entrenador = User::firstOrCreate(
            ['id_ea' => 'Sebastianicc'],
            [
                'name' => 'Sebastianicc',
                'email' => 'sebastianicc@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower("Aurinegro Esports");
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $coloresHex = [
            'negro' => '#000000',
            'amarillo' => '#FFFF00',
            'blanco' => '#FFFFFF',
        ];

        $equipo = Equipo::create([
            'nombre' => 'Aurinegro Esports',
            'color_primario' => $coloresHex['negro'],
            'color_secundario' => $coloresHex['amarillo'],
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $entrenador->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [91, 'Und3rhisbedz', 'portero'],
            [26, 'markramos26', 'defensa'],
            [4, 'Sebastianicc', 'defensa'],
            [15, 'Kevin69cc', 'defensa'],
            [3, 'Vitokinho_087', 'defensa'],
            [16, 'alandean10', 'defensa'],
            [8, 'fabo_destroy', 'medio campista'],
            [20, 'boris_henriquez', 'medio campista'],
            [5, 'Vicarious088', 'medio campista'],
            [40, 'gm-hcarrasko', 'medio campista'],
            [92, 'Naachofdez9', 'medio campista'],
            [1, 'BryanSoft', 'medio campista'],
            [96, 'lSotinhoo_', 'medio campista'],
            [10, 'Krv_Xulo', 'medio campista'],
            [69, 'XxestebanxX', 'volante'],
            [36, 'AGUS-ALBO98', 'volante'],
            [25, 'eyelessthanhuman', 'volante'],
            [17, 'Lucho9956', 'delantero'],
            [11, 'Dragking_2', 'delantero'],
            [9, 'marco_villagran', 'delantero'],
            [7, 'kristiansinhio', 'volante'],
        ];

        foreach ($jugadores as [$numero, $id_ea, $rol]) {
            $jugador = User::firstOrCreate(
                ['id_ea' => $id_ea],
                [
                    'name' => $id_ea,
                    'email' => strtolower(preg_replace('/[^a-z0-9]/i', '', $id_ea)) . '@mail.com',
                    'password' => bcrypt('password'),
                    'foto' => 'images/users/default-user.png',
                    'role' => 'jugador',
                ]
            );

            $posicion = match (strtolower($rol)) {
                'portero' => 'POR',
                'defensa' => 'DFC',
                'medio campista', 'volante' => 'MC',
                'delantero' => 'DC',
                default => 'MC',
            };

            Plantilla::create([
                'id_equipo' => $equipo->id,
                'id_jugador' => $jugador->id,
                'numero' => $numero,
                'rol' => 'jugador',
                'posicion' => $posicion,
            ]);
        }
    }
}
