<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear los 3 usuarios específicos
        User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password'),
            'role' => 'administrador',
            'id_ea' => 'admin001',
            'foto' => 'images/users/default-user.png',
        ]);

        User::create([
            'name' => 'Jugador',
            'email' => 'jugador@demo.com',
            'password' => bcrypt('password'),
            'role' => 'jugador',
            'id_ea' => 'jugador001',
            'foto' => 'images/users/default-user.png',
        ]);

        User::create([
            'name' => 'Entrenador',
            'email' => 'entrenador@demo.com',
            'password' => bcrypt('password'),
            'role' => 'entrenador',
            'id_ea' => 'entrenador001',
            'foto' => 'images/users/default-user.png',
        ]);

        User::create([
            'name' => 'comunidadamc',
            'email' => 'comunidadamc@gmail.com',
            'password' => bcrypt('Galitroamc1.'),
            'role' => 'administrador',
            'id_ea' => 'galitro',
            'foto' => 'images/users/default-user.png',
        ]);

        // Crear 150 usuarios aleatorios usando factory
        //User::factory(150)->create();
    }
}
