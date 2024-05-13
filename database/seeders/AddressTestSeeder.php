<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Storage;

class AddressTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('database')->copy('database1.sqlite', 'database.sqlite');
    }
}
