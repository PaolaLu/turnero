<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Datos iniciales de una instalación nueva.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
