<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Noti;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\WorkPlate;
use Cache;
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
            'order_id' => $orderId,
            'name' => fake()->name(),
            'mass' => random_int(0, 10000000),
            'desc' => fake()->text(),
        ]))->save();
    }

    private function createOrder($addressIds)
    {
        $address = $this->random($addressIds);
        $senderAddress = [$address, 'hn'];
        $receiverAddress = [$address, 'hn'];
        $order = new Order([
            'type_id' => random_int(9, 12),
            'sender_name' => fake()->name(),
            'sender_address_id' => $senderAddress,
            'sender_phone' => fake()->phoneNumber(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_address_id' => $receiverAddress,
            'status_id' => StatusEnum::CREATE,
        ]);
        $order->save();
        $order->fresh();

        (new Noti([
            'order_id' => $order->id,
            'status_id' => StatusEnum::CREATE,
            'description' => 'tét',
            'from_id' => 1,
            'to_id' => 1,
            'from_address_id' => $senderAddress,
            'to_address_id' => $senderAddress,
            'address_current_id' => $address,
        ]))->save();

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
        $addressIds = Cache::remember('addressIds', now()->addDays(10), function () {
            return DB::connection('sqlite_vn_map')
                ->table('wards')
                ->get(['code'])
                ->pluck('code');
        });
        $n = 10;
        for ($i = 0; $i < $n; $i++) {
            $this->createOrder($addressIds);
        }
    }
}
