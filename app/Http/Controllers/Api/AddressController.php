<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\GetDistrictAddressRequest;
use App\Http\Requests\Address\GetProvinceAddressRequest;
use App\Http\Requests\Address\GetWardAddressRequest;
use DB;

class AddressController extends Controller
{
    public function getAllProvinces(GetProvinceAddressRequest $request)
    {
        return $this->sendSuccess(
            DB::connection('sqlite_vn_map')->table('provinces')->get(),
            'get all provinces'
        );
    }

    public function getAllDistricts(GetDistrictAddressRequest $request)
    {
        return $this->sendSuccess(
            DB::connection('sqlite_vn_map')->table('districts')->where('province_code', '=', $request->code)->get(),
            'get all districts'
        );
    }
    public function getAllWards(GetWardAddressRequest $request)
    {
        return $this->sendSuccess(
            DB::connection('sqlite_vn_map')->table('wards')->where('district_code', '=', $request->code)->get(),
            'get all wards'
        );
    }
}
