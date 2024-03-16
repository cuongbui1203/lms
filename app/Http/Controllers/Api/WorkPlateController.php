<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWPRequest;
use App\Http\Requests\UpdateWarehouseDetailRequest;
use App\Http\Requests\UpdateWPRequest;
use App\Models\WorkPlate;
use Illuminate\Http\Request;

class WorkPlateController extends Controller
{
    public function index()
    {
        $wp = WorkPlate::paginate(config('paginate.wp-list'));
        foreach ($wp as $e) {
            $e->{'address'} = $e->address;
        }
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
        $workPlate->type_id = $request->typeId;
        $workPlate->vung = $request->vung;
        $workPlate->save();

        return $this->sendSuccess($workPlate, 'WorkPlate create success');
    }

    public function addDetail(UpdateWarehouseDetailRequest $request, WorkPlate $workPlate)
    {
        $workPlate->detail()->create(['max_payload' => $request->max_payload, 'payload' => 0]);

        return $this->sendSuccess($workPlate, 'Update Detail success');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkPlate $workPlate)
    {
        // dd($workPlate->getAddressName(30598, 3));
        if ($workPlate->type_id === config('type.workPlate.warehouse')) {
            $workPlate->load('detail');
        }
        $workPlate->load('type');
        $workPlate->{'address'} = $workPlate->address;
        // dd($workPlate);
        return $this->sendSuccess($workPlate, 'Get success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWPRequest $request, WorkPlate $workPlate)
    {
        //
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
