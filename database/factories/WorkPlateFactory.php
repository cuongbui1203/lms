<?php

namespace Database\Factories;

use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkPlate>
 */
class WorkPlateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cap = rand(1, 3);
        $db = DB::connection('sqlite_vn_map');
        $idAddress = $db->table('wards')->get(['code'])->random()->code;
        $vung = getAddressCode($idAddress, $cap);
        $type = rand(1, 3);

        // dd(config('type.workPlate'));
        return [
            'name' => fake()->name(),
            'address_id' => $idAddress,
            'cap' => $cap,
            'vung' => $vung,
            'created_at' => now(),
            'updated_at' => now(),
            'type_id' => $type,
        ];
    }

    // public 
}
