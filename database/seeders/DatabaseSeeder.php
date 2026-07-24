<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SpecialtySeeder::class,
            InsuranceSeeder::class,
            UserSeeder::class,
            PatientSeeder::class,
        ]);
    }
}