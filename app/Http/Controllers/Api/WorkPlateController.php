<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkPlate\CreateWPRequest;
use App\Http\Requests\WorkPlate\UpdateWarehouseDetailRequest;
use App\Http\Requests\WorkPlate\UpdateWPRequest;
use App\Models\WorkPlate;

class WorkPlateController extends Controller
{
    public function index()
    {
        $wp = WorkPlate::all(['id', 'name', 'address_id', 'created_at', 'updated_at', 'type_id']);
        $wp->load('type', 'detail');

        return $this->sendSuccess($wp, 'Get list work plate success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWPRequest $request)
    {
        $workPlate = new WorkPlate();
        $workPlate->name = $request->name;
        $workPlate->address_id = $request->address_id;
        $workPlate->type_id = $request->type_id;
        $workPlate->vung = $request->vung;
        if ($request->type_id === config('type.workPlate.warehouse')) {
            $workPlate->detail()->create([
                'max_payload' => $request->max_payload,
                'payload' => 0,
            ]);
        }

        $workPlate->save();

        return $this->sendSuccess($workPlate, 'WorkPlate create success');
    }

    public function addDetail(UpdateWarehouseDetailRequest $request, WorkPlate $workPlate)
    {
        $workPlate->detail()->create(['max_payload' => $request->max_payload, 'payload' => 0]);
        $workPlate->load('detail', 'type');

        return $this->sendSuccess($workPlate, 'Update Detail success');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkPlate $workPlate)
    {
        if ($workPlate->type_id === config('type.workPlate.warehouse')) {
            $workPlate->load('detail');
        }

        $workPlate->load('type');

        return $this->sendSuccess($workPlate, 'Get success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWPRequest $request, WorkPlate $workPlate)
    {
        $workPlate->name = $request->name ?? $workPlate->name;
        $workPlate->address_id = $request->address_id ?? $request->address_id;
        if (
            $workPlate->type_id === config('type.workPlate.warehouse') &&
            $request->type_id &&
            $request->type_id !== config('type.workPlate.warehouse')
        ) {
            $workPlate->detail()->delete();
        }

        $workPlate->type_id = $request->type_id ?? $workPlate->type_id;
        if ($workPlate->type_id === config('type.workPlate.warehouse')) {
            if ($workPlate->detail) {
                $workPlate->detail->max_payload = $request->max_payload ?? $workPlate->detail->max_payload;
            } else {
                $workPlate->detail()->create(['max_payload' => $request->max_payload ?? 0, 'payload' => 0]);
            }
        }

        $workPlate->load('type');

        return $this->sendSuccess($workPlate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkPlate $workPlate)
    {
        if ($workPlate->type_id === config('type.workPlate.warehouse')) {
            $workPlate->detail->delete();
        }

        $workPlate->delete();

        return response()->noContent();
    }
}
