<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('images')->insert(
            [
            'id'=>0,
            'url'=>'null',
            'created_at'=>now(),
            'updated_at'=>now()
            ]
        );
    }
}
