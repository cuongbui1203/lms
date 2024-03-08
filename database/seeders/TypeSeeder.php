<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert(
            [
                'id'=>config('type.workPlate.warehouse'),
                'name'=>'warehouse',
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.workPlate.transactionPoint'),
                'name'=>'transactionPoint',
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.workPlate.transshipmentPoint'),
                'name'=>'transshipmentPoint'
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.vehicle.van'),
                'name'=>'van'
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.vehicle.truck'),
                'name'=>'truck'
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.vehicle.tractor'),
                'name'=>'tractor'
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.vehicle.freezing'),
                'name'=>'freezingCar'
            ]
        );
        DB::table('types')->insert(
            [
                'id'=>config('type.vehicle.container'),
                'name'=>'container'
            ]
        );
    }
}
