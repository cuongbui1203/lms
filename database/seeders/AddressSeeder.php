<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Storage;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storage = Storage::disk('database');
        DB::connection('sqlite_vn_map')
            ->unprepared($storage->get('sql/ImportData_vn_units.sql'));
    }
}
