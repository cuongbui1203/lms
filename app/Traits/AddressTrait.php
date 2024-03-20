<?php

namespace App\Traits;

use App\Enums\AddressTypeEnum;
use DB;
use Exception;

/**
 * Address Trait
 * 
 * @method string getAddressName(int $id,int $type)
 */
trait AddressTrait
{
    public function getAddressName(int $id, int $type)
    {
        $table = "";
        switch ($type) {
            case 1:
                $table = "provinces";
                break;
            case 2:
                $table = "districts";
                break;
            case 3:
                $table = "wards";
                break;
            default:
                throw new Exception("unknown type");
        }
        // return $table;
        try {
            $address = DB::connection('sqlite_vn_map')
                ->table(DB::raw("$table t"))
                ->where("t.code", $id)
                ->select(DB::raw("t.name"))
                ->get();
            return $address[0]->name;
        } catch (Exception $e) {
            return "unknown";
        }
    }

    /**
     * lay code cua 1 dia chi tu id cua wards
     *
     * @param string $id
     * @param integer $type
     * @return string|null
     * @throws Exception
     */
    public function getAddressCode(string $id, int $type)
    {
        $res = '';
        switch ($type) {
            case AddressTypeEnum::Province:
                $res =  DB::connection('sqlite_vn_map')
                    ->table(DB::raw("wards w"))
                    ->join(DB::raw("districts d"), "d.code", "=", "w.district_code")
                    ->join(DB::raw("provinces p"), "p.code", "=", "d.province_code")
                    ->where(DB::raw("w.code"), "=", $id)
                    ->select(DB::raw("p.code"))
                    ->first();

                break;
            case AddressTypeEnum::District:
                $res =  DB::connection('sqlite_vn_map')
                    ->table(DB::raw("wards w"))
                    ->join(DB::raw("districts d"), "d.code", "=", "w.district_code")
                    ->where(DB::raw("w.code"), "=", $id)
                    ->select(DB::raw("d.code"))
                    ->first();

                break;
            case AddressTypeEnum::Ward:
                return $id;
            default:
                throw new Exception("unknown type");
        }
        return $res->code;
    }

    public function getAddress($addressId)
    {
        $res =  DB::connection('sqlite_vn_map')
            ->table(DB::raw("wards w"))
            ->join(DB::raw("districts d"), "d.code", "=", "w.district_code")
            ->join(DB::raw("provinces p"), "d.province_code", "=", "p.code")
            ->select(
                DB::raw("p.full_name as provinceName"),
                DB::raw("d.full_name as districtName"),
                DB::raw("w.full_name as wardName"),
                DB::raw('p.code as provinceCode'),
                DB::raw('d.code as districtCode'),
                DB::raw('w.code as wardCode')
            )
            ->where(DB::raw("w.code"), $addressId)
            ->first();
        if (!$res) {
            throw new Exception("loi truy van cho address co id: " . $addressId, 1);
        }
        $res = (object)array(
            'provinceCode' => $res->provinceCode,
            'districtCode' => $res->districtCode,
            'wardCode' => $res->wardCode,
            'province' => $res->provinceName,
            'district' => $res->districtName,
            'ward' => $res->wardName,
        );
        return $res;
    }
}
