<?php

namespace App\Traits;

use App\Enums\AddressTypeEnum;
use DB;
use Exception;

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
                // break;
            default:
                throw new Exception("unknown type");
        }
        return $res->code;
    }

    public function getAddress($addressId)
    {
        // $add = Address::where("id", $id)->first();
        // // return $add;
        // if (!$add) {
        //     throw new Exception("Khong tim thay Address record co id: " . $id, 1);
        // }
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
            'province' => $res->provinceName,
            'provinceCode' => $res->provinceCode,
            'districtCode' => $res->districtCode,
            'wardCode' => $res->wardCode,
            'district' => $res->districtName,
            'ward' => $res->wardName,
        );
        return $res;
    }

    public function getAllProvinces()
    {
        try {
            $res =  DB::connection('sqlite_vn_map')->table(DB::raw("provinces p"))
                ->select(DB::raw("code as id"), DB::raw('full_name as name'))
                ->get();
            return $this->sendResponse($res, 'get all provinces success');
        } catch (Exception $e) {
            return $this->sendError("cannot get provinces", $e->getMessage());
        }
    }
    public function getAllDistricts()
    {
        if (!isset($_GET['id'])) {
            return $this->sendError([], 'k cos code province');
        }
        $id = $_GET['id'];
        try {
            $res =  DB::connection('sqlite_vn_map')->table(DB::raw("districts d"))
                ->where(DB::raw("d.province_code"), $id)
                ->select(DB::raw("code as id"),  DB::raw('full_name as name'))
                ->get();
            return $this->sendResponse($res, 'get all provinces success');
        } catch (Exception $e) {
            return $this->sendError("cannot get provinces", $e->getMessage());
        }
    }
    public function getAllWards()
    {
        if (!isset($_GET['id'])) {
            return $this->sendError([], 'k cos code district');
        }
        $id = $_GET['id'];
        try {
            $res =  DB::connection('sqlite_vn_map')->table(DB::raw("wards w"))
                ->where(DB::raw("w.district_code"), $id)
                ->select(DB::raw("code as id"),  DB::raw('full_name as name'))
                ->get();
            return $this->sendResponse($res, 'get all provinces success');
        } catch (Exception $e) {
            return $this->sendError("cannot get provinces", $e->getMessage());
        }
    }
}
