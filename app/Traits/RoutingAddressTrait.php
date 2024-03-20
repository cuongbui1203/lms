<?php

namespace App\Traits;

use App\Enums\AddressTypeEnum;
use App\Models\Order;
use App\Models\WorkPlate;

trait RoutingAddressTrait
{
    public function routing(Order $order)
    {
        $idAddressHT = $order->notifications->last()->to->address_id;
        $vungHt = $order->notifications->last()->to->vung;
        $capHt = $order->notifications->last()->to->cap;
        $idAddressN = $order->receiver_address_id;
        $status = $order->status_id;
        // return [$idAddressHT, $vungHt, $capHt, $idAddressN, $status];
        // dd(WorkPlate::where('vung', '=', $order->getAddressCode($idAddressHT, AddressTypeEnum::Province))->first());
        if ($idAddressHT == $idAddressN && $capHt == AddressTypeEnum::Ward) {
            // $order->statusDetail->status_id = StatusEnum::Shipping;
            // $order->statusDetail->transport_id = '';
            return "ship";
        }
        if ($capHt == AddressTypeEnum::Ward) {
            $res = WorkPlate::where(
                'vung',
                '=',
                $order->getAddressCode($idAddressHT, AddressTypeEnum::District)
            )
                ->first();
            if (!$res) return 'shipping';
            return $res->id;
        }

        if ($capHt == AddressTypeEnum::District) {
            $codeNn = $order->getAddressCode($idAddressN, AddressTypeEnum::District);
            if ($codeNn == $vungHt) {
                $res = WorkPlate::where('vung', '=', $idAddressN)->first();
            } else {
                $res = WorkPlate::where('vung', '=', $order->getAddressCode($idAddressHT, AddressTypeEnum::Province))->first();
            }
            return $res->id;
        }

        if ($capHt == AddressTypeEnum::Province) {
            if ($vungHt == $order->getAddressCode($idAddressN, AddressTypeEnum::Province)) {
                $res = WorkPlate::where('vung', '=', $order->getAddressCode($idAddressN, AddressTypeEnum::District))->first();
            } else {
                $res = WorkPlate::where('vung', '=', $order->getAddressCode($idAddressN, AddressTypeEnum::Province))->first();
            }
            return $res->id;
        }

        $idVungNn = $order->getAddressCode($idAddressHT, AddressTypeEnum::District);


        return [$idAddressHT, $idAddressN, $vungHt, $idVungNn, $capHt];
    }
}
