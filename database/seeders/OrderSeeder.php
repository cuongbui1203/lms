<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    private function random($array)
    {
        return $array[rand(0, count($array) - 1)];
    }

    private function createDetail($orderId)
    {
        (new OrderDetail([
            'type_id' => random_int(9, 12),
            'order_id' => $orderId,
            'name' => fake()->name(),
            'mass' => random_int(0, 10000000),
            'desc' => fake()->text(),
        ]))->save();
    }

    private function createOrder($addressIds)
    {
        $order = new Order([
            'sender_name' => fake()->name(),
            'sender_address_id' => $this->random($addressIds),
            'sender_phone' => fake()->phoneNumber(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_address_id' => $this->random($addressIds),
        ]);
        $order->save();

        $n = rand(1, 20);
        while ($n > 0) {
            $n--;
            $this->createDetail($order->id);
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addressIds = DB::connection('sqlite_vn_map')
            ->table('wards')
            ->get(['code'])
            ->pluck('code');
        $n = 10;
        for ($i = 0; $i < $n; $i++) {
            $this->createOrder($addressIds);
        }
    }
}
