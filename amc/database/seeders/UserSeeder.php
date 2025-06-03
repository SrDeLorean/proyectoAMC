<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'administrador',
        ]);

        User::create([
            'name' => 'Jugador',
            'email' => 'jugador@demo.com',
            'password' => Hash::make('password'),
            'role' => 'jugador',
        ]);

        User::create([
            'name' => 'Entrenador',
            'email' => 'entrenador@demo.com',
            'password' => Hash::make('password'),
            'role' => 'entrenador',
        ]);
    }
}
