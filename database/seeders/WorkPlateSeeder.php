<?php

namespace Database\Seeders;

use App\Models\WarehouseDetail;
use App\Models\WorkPlate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkPlateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkPlate::factory(10)->create()->each(function ($e) {
            if ($e->type_id === config('type.workPlate.warehouse')) {
                WarehouseDetail::factory(1)->create([
                    'wp_id' => $e->id,
                ]);
            }
        });
    }
}
