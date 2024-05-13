<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                AddressSeeder::class,
                RoleSeeder::class,
                TypeSeeder::class,
                StatusSeeder::class,
                WorkPlateSeeder::class,
                UserSeeder::class,
                OrderSeeder::class,
                OrderTestSeeder::class,
            ]
        );
    }
}
