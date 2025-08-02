<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoResistenciaSpsSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-4-2']);

        $dt = User::firstOrCreate(
            ['id_ea' => 'Jynn9999'],
            [
                'name' => 'Luis',
                'email' => 'jynn9999@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $codt1 = User::firstOrCreate(
            ['id_ea' => 'Anphisan'],
            [
                'name' => 'Antonio',
                'email' => 'anphisan@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $codt2 = User::firstOrCreate(
            ['id_ea' => 'Eztebanleiva'],
            [
                'name' => 'Esteban',
                'email' => 'eztebanleiva@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logoNombre = strtolower('Resistencia SPS');
        $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $logoNombre);
        $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre);

        $equipo = Equipo::create([
            'nombre' => 'Resistencia SPS',
            'color_primario' => '#FFA500',
            'color_secundario' => '#000000',
            'id_formacion' => $formacion->id,
            'id_usuario' => $dt->id,
            'id_usuario2' => $codt1->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [3, 'DieseLcaVcl', 'defensa'],
            [7, 'XBlueL2R2', 'volante'],
            [88, 'Jynn9999', 'defensa'],
            [11, 'Eztebanleiva', 'delantero'],
            [99, 'Glamodrama83', 'delantero'],
            [93, 'PRuggieri93', 'volante'],
            [14, 'Anphisan', 'defensa'],
            [8, 'Xlnxacho420', 'medio campista'],
            [5, 'Matiking6047', 'volante'],
            [1, 'LevYashin2999', 'portero'],
            [15, 'Pipethai', 'defensa'],
            [16, 'los4nt1f3k4', 'medio campista'],
            [69, 'nicasio_arcano77', 'defensa'],
            [66, 'MillanoDW24', 'medio campista'],
            [13, 'Flipdani', 'medio campista'],
            [6, 'machineChilea', 'medio campista'],
            [10, 'Eldiabloo23', 'delantero'],
            [9, 'Tommy_shelby', 'delantero'],
        ];

        $mapPos = [
            'portero' => 'POR',
            'defensa' => 'DFC',
            'medio campista' => 'MC',
            'volante' => 'MI',
            'delantero' => 'DC',
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $rol]) {
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
                'posicion' => $mapPos[strtolower($rol)] ?? 'MC',
            ]);
        }
    }
}
