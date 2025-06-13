<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\FormacionSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\EquipoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(FormacionSeeder::class);
        $this->call(EquipoSeeder::class);
    }
}
