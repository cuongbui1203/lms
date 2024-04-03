<?php

namespace Database\Factories;

use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ids = DB::connection('sqlite_vn_map')->table('wards')->get(['code']);

        return [
            'sender_name' => fake()->name(),
            'sender_address_id' => $ids->random()->code,
            'sender_phone' => fake()->phoneNumber(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_address_id' => $ids->random()->code,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
