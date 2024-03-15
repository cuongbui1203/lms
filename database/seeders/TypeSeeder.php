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
                'id' => config('type.workPlate.warehouse'),
                'name' => 'warehouse',
                'for' => config('type.for.workPlate'),
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.workPlate.transactionPoint'),
                'for' => config('type.for.workPlate'),
                'name' => 'transactionPoint',
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.workPlate.transshipmentPoint'),
                'for' => config('type.for.workPlate'),
                'name' => 'transshipmentPoint'
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.vehicle.van'),
                'for' => config('type.for.vehicle'),
                'name' => 'van'
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.vehicle.truck'),
                'for' => config('type.for.vehicle'),
                'name' => 'truck'
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.vehicle.tractor'),
                'for' => config('type.for.vehicle'),
                'name' => 'tractor'
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.vehicle.freezing'),
                'for' => config('type.for.vehicle'),
                'name' => 'freezingCar'
            ]
        );
        DB::table('types')->insert(
            [
                'id' => config('type.vehicle.container'),
                'for' => config('type.for.vehicle'),
                'name' => 'container'
            ]
        );
    }
}
