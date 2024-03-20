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
                RoleSeeder::class,
                TypeSeeder::class,
                StatusSeeder::class,
                AddressSeeder::class,
                OrderSeeder::class,
            ]
        );
    }
}
