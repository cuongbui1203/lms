<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetListRequest;
use App\Http\Requests\Order\ListOrderIdRequest;
use App\Http\Requests\Vehicle\CreateVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Models\Vehicle;
use DB;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetListRequest $request)
    {
        $pageSize = $request->pageSize ?? config('paginate.wp-list');
        $page = $request->page ?? 1;
        $relationships = ['driver', 'type', 'goodsType'];
        $vehicles = Vehicle::all()->paginate($pageSize, $page, $relationships);

        return $this->sendSuccess($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVehicleRequest $request)
    {
        $vehicle = new Vehicle();
        $vehicle->name = $request->name;
        $vehicle->type_id = $request->typeId;
        $vehicle->payload = $request->payload;
        $vehicle->goods_type = $request->goodsType;
        $vehicle->save();

        return $this->sendSuccess([
            'vehicle' => $vehicle,
        ], 'create vehicle success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['type', 'driver', 'goodsType']);

        return $this->sendSuccess($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->name = $request->name ?? $vehicle->name;
        $vehicle->driver_id = $request->driverId ?? $vehicle->driver_id;
        $vehicle->payload = $request->payload ?? $vehicle->payload;
        $vehicle->goods_type = $request->goodsType ?? $vehicle->goods_type;

        $vehicle->save();

        return $this->sendSuccess([], 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return $this->sendSuccess([], 'delete success');
    }

    public function getSuggestionVehicle(ListOrderIdRequest $request)
    {
        $typeIds = DB::table('orders')->whereIn('id', $request->getOrders())->get(['type_id'])->pluck('type_id');
        $vehicles = Vehicle::whereIn('goods_type', $typeIds)->get();

        return $this->sendSuccess($vehicles);
    }
}
