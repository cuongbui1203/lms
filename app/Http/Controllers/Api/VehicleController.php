<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicle\CreateVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all()->load(['driver', 'type']);

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

        $vehicle->save();

        return $this->sendSuccess([
            'vehicle'=>$vehicle
        ], 'create vehicle success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['type', 'driver']);

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
}
