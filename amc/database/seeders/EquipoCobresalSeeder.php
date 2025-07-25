<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;

class EquipoCobresalSeeder extends Seeder
{
    public function run(): void
    {
        $formacion = Formacion::firstOrCreate(['nombre' => '4-3-3']);

        $dueno = User::firstOrCreate(
            ['id_ea' => 'Fernaaaandoandre'],
            [
                'name' => 'Fernaaaandoandre',
                'email' => 'fernaaaandoandre@mail.com',
                'password' => bcrypt('password'),
                'foto' => 'images/users/default-user.png',
                'role' => 'entrenador',
            ]
        );

        $logo = 'images/equipos/cobresal.png';

        $equipo = Equipo::create([
            'nombre' => 'Cobresal',
            'color_primario' => '#FFA500', // Naranjo
            'color_secundario' => '#FFFFFF', // Blanco
            'id_formacion' => $formacion->id,
            'id_usuario' => $dueno->id,
            'id_usuario2' => $dueno->id,
            'logo' => "images/equipos/default-equipo.png",
        ]);

        $jugadores = [
            [30, 'Fernaaaandoandre', 'DELANTERO', 'Chile'],
            [14, 'NickoJR14', 'MEDIOCAMPISTA', 'Chile'],
            [0, 'ESTEBANZF17', 'DEFENSA', 'Chile'],
            [13, 'Csaez2006', 'DEFENSA', 'Chile'],
            [69, 'TMG-MOROCHO', 'VOLANTE', 'Colombia'],
            [20, 'el-vlady-', 'MEDIOCAMPISTA', 'Chile'],
            [88, 'YerkoG19', 'MEDIOCAMPISTA', 'Chile'],
            [24, 'yAlancvto-', 'PORTERO', 'Chile'],
            [5, 'MCS_COOLORO', 'DEFENSA', 'Chile'],
            [13, 'F_rivas13', 'MEDIOCAMPISTA', 'Chile'],
            [21, 'shaolinfantastic-', 'VOLANTE', 'Chile'],
            [17, 'Vichoney22', 'DELANTERO', 'Chile'],
            [7, 'SantitosJr10', 'DELANTERO', 'Chile'],
            [13, 'Javi6956', 'DEFENSA', 'Chile'],
        ];

        $emailify = fn($id) => strtolower(preg_replace('/[^a-z0-9]/i', '', $id)) . '@mail.com';

        foreach ($jugadores as [$numero, $id_ea, $posicion, $nacionalidad]) {
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
                'posicion' => strtoupper(str_replace([' ', '-'], '', $posicion)),
            ]);
        }
    }
}
