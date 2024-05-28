<?php

use App\Enums\AddressTypeEnum;
use App\Models\Image;
use App\Models\Order;
use App\Models\WorkPlate;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getLastSegmentRegex')) {
    function getLastSegmentRegex(string $string)
    {
        if (preg_match('/\/([^\/]+)$/', $string, $matches)) {
            return (int) $matches[1];
        }

        return '';
    }
}

if (!function_exists('storeImage')) {
    function storeImage($path, $file)
    {
        $pathImage = Storage::put($path, $file);
        $image = new Image();
        $image->url = $pathImage;
        $image->save();

        return $image->id;
    }
}

if (!function_exists('deleteImage')) {
    /**
     * delete image
     *
     * @param int $id
     * @return void
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    function deleteImage($id)
    {
        $image = Image::findOrFail($id);
        $path = $image->url;
        Storage::delete($path);
        $image->delete();
    }
}

if (!function_exists('api_path')) {
    /**
     * get path of api directory
     *
     * @param string $path
     * @return string
     */
    function api_path($path = '')
    {
        return base_path('/routes/api/' . $path);
    }
}

if (!function_exists('getAddressName')) {
    function getAddressName(string $id, int $type)
    {
        $table = '';
        switch ($type) {
            case 1:
                $table = 'provinces';

                break;
            case 2:
                $table = 'districts';

                break;
            case 3:
                $table = 'wards';

                break;
            default:
                throw new Exception('unknown type');
        }

        // return $table;
        try {
            $address = DB::connection('sqlite_vn_map')
                ->table(DB::raw("$table t"))
                ->where('t.code', $id)
                ->select(DB::raw('t.name'))
                ->get();

            return $address[0]->name;
        } catch (Exception $e) {
            return 'unknown';
        }
    }
}

if (!function_exists('getAddressCode')) {
    /**
     * lay code cua 1 dia chi tu id cua wards
     *
     * @param string $id address wards id
     * @param int $type 1,2,3
     * @return string|null
     * @throws Exception
     */
    function getAddressCode(string $id, int $type)
    {
        $res = '';
        // dd($type);
        switch ($type) {
            case AddressTypeEnum::PROVINCE:
                $res = DB::connection('sqlite_vn_map')
                    ->table(DB::raw('wards w'))
                    ->join(DB::raw('districts d'), 'd.code', '=', 'w.district_code')
                    ->join(DB::raw('provinces p'), 'p.code', '=', 'd.province_code')
                    ->where(DB::raw('w.code'), '=', $id)
                    ->select(DB::raw('p.code'))
                    ->first();

                break;
            case AddressTypeEnum::DISTRICT:
                $res = DB::connection('sqlite_vn_map')
                    ->table(DB::raw('wards w'))
                    ->join(DB::raw('districts d'), 'd.code', '=', 'w.district_code')
                    ->where(DB::raw('w.code'), '=', $id)
                    ->select(DB::raw('d.code'))
                    ->first();

                break;
            case AddressTypeEnum::WARD:
                return $id;
            default:
                throw new Exception('unknown type');
        }

        return $res->code;
    }
}

if (!function_exists('getAddress')) {
    function getAddress(string | null $addressId)
    {
        if (is_null($addressId) || $addressId === '') { // phpcs:ignore
            return null;
        }

        try {
            $res = DB::connection('sqlite_vn_map')
                ->table(DB::raw('wards w'))
                ->join(DB::raw('districts d'), 'd.code', '=', 'w.district_code')
                ->join(DB::raw('provinces p'), 'd.province_code', '=', 'p.code')
                ->select(
                    DB::raw('p.full_name as provinceName'),
                    DB::raw('d.full_name as districtName'),
                    DB::raw('w.full_name as wardName'),
                    DB::raw('p.code as provinceCode'),
                    DB::raw('d.code as districtCode'),
                    DB::raw('w.code as wardCode')
                )
                ->where(DB::raw('w.code'), $addressId)
                ->first();
        } catch (Exception $e) {
            throw $e;
        }

        if (!$res) {
            throw new Exception('loi truy van cho address co id: ' . $addressId, 1);
        }

        $res = (object) [
            'provinceCode' => $res->provinceCode,
            'districtCode' => $res->districtCode,
            'wardCode' => $res->wardCode,
            'province' => $res->provinceName,
            'district' => $res->districtName,
            'ward' => $res->wardName,
        ];

        return $res;
    }
}

if (!function_exists('getAddressRank')) {
    /**
     * get rank of address over addressID
     *
     * @param string $addressId
     * @return int|string
     */
    function getAddressRank(string $addressId)
    {
        $db = DB::connection('sqlite_vn_map');
        $p = $db->table('provinces')->where('code', $addressId);
        $d = $db->table('districts')->where('code', $addressId);
        $w = $db->table('wards')->where('code', $addressId);

        if ($p->exists()) {
            return AddressTypeEnum::PROVINCE;
        }

        if ($d->exists()) {
            return AddressTypeEnum::DISTRICT;
        }

        if ($w->exists()) {
            return AddressTypeEnum::WARD;
        }

        return -1;
    }
}

if (!function_exists('routing')) {
    /**
     * routing the order to the next transportPoint, Transaction or next state shipping.
     *
     * @param Order $order
     * @return WorkPlate|null
     */
    function routing(Order $order)
    {
        $idAddressHT = $order->notifications->last()->to->address->wardCode;
        $vungHt = $order->notifications->last()->to->vung;
        $capHt = $order->notifications->last()->to->cap;
        $idAddressN = $order->receiver_address->wardCode;

        if ($idAddressHT === $idAddressN && $capHt === AddressTypeEnum::WARD) {
            return null;
        }

        $resMain = null;
        switch ($capHt) {
            case AddressTypeEnum::WARD:
                $res = WorkPlate::where(
                    'vung',
                    '=',
                    getAddressCode($idAddressHT, AddressTypeEnum::DISTRICT)
                )
                    ->first();
                if (!$res) {
                    $resMain = null;

                    break;
                }

                $resMain = $res;

                break;
            case AddressTypeEnum::DISTRICT:
                $codeNn = getAddressCode($idAddressN, AddressTypeEnum::DISTRICT);
                if ($codeNn === $vungHt) {
                    $res = WorkPlate::where('vung', '=', $idAddressN)->first();
                } else {
                    $res = WorkPlate::where('vung', '=', getAddressCode($idAddressHT, AddressTypeEnum::PROVINCE))
                        ->first('id');
                }

                $resMain = $res;

                break;
            case AddressTypeEnum::PROVINCE:
                if ($vungHt === getAddressCode($idAddressN, AddressTypeEnum::PROVINCE)) {
                    $res = WorkPlate::where('vung', '=', getAddressCode($idAddressN, AddressTypeEnum::DISTRICT))
                        ->first();
                } else {
                    $res = WorkPlate::where('vung', '=', getAddressCode($idAddressN, AddressTypeEnum::PROVINCE))
                        ->first();
                }

                $resMain = $res;

                break;
            default:
                break;
        }

        return $resMain;
    }
}

if (!function_exists('routingAnother')) {
    /**
     * lay vi tri goi y tiep theo
     *
     * @param Order $order order can xu ly
     * @return WorkPlate|null
     */
    function routingAnother(Order $order, bool $ship = false)
    {
        $noti = $order->notifications->last();
        $idAddressHT = $noti->address_current_id; // address id hiện tại
        $capHt = getAddressRank($idAddressHT); // cap hien tai
        $idAddressN = $order->receiver_address->wardCode; // address id ng nhan
        $res = null;

        while ($capHt < AddressTypeEnum::PROVINCE) {
            $capHt++;
            $wp = WorkPlate::where('vung', getAddressCode($idAddressHT, $capHt))->first();
            if ($wp) {
                $res = $wp;

                break;
            }
        }

        if ($res || $ship) {
            return $res;
        }

        while ($capHt >= AddressTypeEnum::WARD) {
            $wp = WorkPlate::where('vung', getAddressCode($idAddressN, $capHt))->first();
            if ($wp) {
                $res = $wp;

                break;
            }

            $capHt--;
        }

        if ($res) {
            return $res;
        }

        return null;
    }
}
