<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::pluck('id')->toArray();

        $nombres = [
            'Leones Rojos', 'Tigres del Norte', 'Águilas Doradas', 'Dragones Negros',
            'Furia Azul', 'Guerreros del Sur', 'Halcones del Este', 'Panteras Verdes',
            'Truenos del Oeste', 'Cóndores Andinos'
        ];

        foreach ($nombres as $nombre) {
            Equipo::create([
                'nombre' => $nombre,
                'descripcion' => 'Equipo de fútbol competitivo.',
                'color_primario' => fake()->safeHexColor(),
                'color_secundario' => fake()->safeHexColor(),
                'logo' => null, // Puedes reemplazar esto por una ruta real o storage path
                'id_formacion' => null, // O asigna una formación válida si ya existen
                'instagram' => 'https://instagram.com/' . fake()->userName(),
                'twitch' => 'https://twitch.tv/' . fake()->userName(),
                'youtube' => 'https://youtube.com/' . fake()->userName(),
                'id_usuario' => fake()->randomElement($usuarios),
                'id_usuario2' => fake()->randomElement($usuarios),
            ]);
        }
    }
}
