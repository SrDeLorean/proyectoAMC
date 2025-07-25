<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formaciones = [
            '4-4-2',
            '4-3-3',
            '3-5-2',
            '5-3-2',
            '4-2-3-1',
            '4-1-4-1',
            '3-4-3',
            '4-5-1',
            '3-6-1',
            '5-4-1',
            '4-3-2-1',
            '4-3-1-2',
            '3-4-1-2',
            '4-2-2-2',
            '4-3-1-2',
            '4-1-2-1-2',
        ];

        foreach ($formaciones as $nombre) {
            DB::table('formaciones')->insert([
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
