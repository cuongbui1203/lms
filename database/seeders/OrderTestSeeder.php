<?php

namespace Database\Seeders;

use App\Enums\AddressTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Noti;
use App\Models\Order;
use App\Models\WorkPlate;
use Illuminate\Database\Seeder;

class OrderTestSeeder extends Seeder
{

    private function createWorkPlate($name, $addressId, $type, $cap)
    {
        $res = WorkPlate::create([
            'name' => $name,
            'address_id' => $addressId,
            'type_id' => $type,
            'cap' => $cap,
            'vung' => getAddressCode($addressId, $cap),
        ]);
        return $res->id;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idGui = $this->createWorkPlate(
            'gui',
            '27280',
            config('type.workPlate.transactionPoint'),
            AddressTypeEnum::Ward
        );
        $this->createWorkPlate(
            'tt1',
            '27280',
            config('type.workPlate.transactionPoint'),
            AddressTypeEnum::District
        );
        $this->createWorkPlate(
            'tt2',
            '27280',
            config('type.workPlate.transactionPoint'),
            AddressTypeEnum::Province
        );


        $order = new Order();
        $order->sender_name = 'senderName';
        $order->sender_phone = '0123456789';
        $order->sender_address_id = '27280';
        $order->receiver_name = 'receiver';
        $order->receiver_phone = '123123123';
        $order->receiver_address_id = '27280';

        $order->save();

        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $idGui;
        $notification->to_id = $idGui;
        $notification->description = "";
        $notification->status_id = StatusEnum::Create;

        $notification->save();
    }
}
