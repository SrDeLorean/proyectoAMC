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
            'UNITED FOREVER',
            'AYSEN ESPORTS',
            'DEPORTES CONCEPCIÓN',
            'WHISKOLA ESPORTS',
            'SUCCESSORS',
            'MUNI MUNI ESP',
            'GRIZZLY GAMING',
            'JUVENTUD AVANCE ESP',
            'FC CHANCHITOS',
            'MADNESS CF',
            'INFAMES ESPORTS',
            'FC GUNS AND ROSES',
            'WANDERERS ESPORTS',
            'CONCEPCIÓN CITY CLU',
        ];

        foreach ($nombres as $nombre) {
            // Convertir nombre a formato para el logo, ej: "UNITED FOREVER" => "united_forever.png"
            $logoNombre = strtolower($nombre);
            $logoNombre = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç'], ['_', 'a', 'e', 'i', 'o', 'u', 'n', 'u', 'c'], $logoNombre);
            $logoNombre = preg_replace('/[^a-z0-9_]/', '', $logoNombre); // elimina caracteres no alfanuméricos ni guiones bajos

            Equipo::create([
                'nombre' => $nombre,
                'descripcion' => 'Equipo de fútbol competitivo.',
                'color_primario' => fake()->safeHexColor(),
                'color_secundario' => fake()->safeHexColor(),
                'logo' => "images/equipos/{$logoNombre}.png",
                'id_formacion' => null,
                'instagram' => 'https://instagram.com/' . fake()->userName(),
                'twitch' => 'https://twitch.tv/' . fake()->userName(),
                'youtube' => 'https://youtube.com/' . fake()->userName(),
                'id_usuario' => fake()->randomElement($usuarios),
                'id_usuario2' => fake()->randomElement($usuarios),
            ]);
        }
    }
}
