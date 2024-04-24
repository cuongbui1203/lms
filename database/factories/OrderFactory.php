<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected static Collection $ids;

    public function __construct()
    {
        $this::$ids = DB::connection('sqlite_vn_map')->table('wards')->get(['code']);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_name' => fake()->name(),
            'sender_address_id' => $this->ids->random()->code,
            'sender_phone' => fake()->phoneNumber(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_address_id' => $this->ids->random()->code,
            'status_id' => StatusEnum::CREATE,
            'type_id' => rand(9, 12),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
