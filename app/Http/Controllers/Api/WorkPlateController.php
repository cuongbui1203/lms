<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWPRequest;
use App\Http\Requests\UpdateWPRequest;
use App\Models\WorkPlate;
use Illuminate\Http\Request;

class WorkPlateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWPRequest $request)
    {
        // dd('d');
        $workPlate = new WorkPlate();
        $workPlate->name = $request->name;
        $workPlate->address = $request->address;
        $workPlate->type_id = $request->typeId;
        $workPlate->save();
        return $this->sendSuccess($workPlate, 'WorkPlate create success');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkPlate $workPlate)
    {
        if($workPlate->type_id === config('type.workPlate.warehouse')) {
            $workPlate->load('detail');
        }

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
        if($workPlate->type_id === config('type.workPlate.warehouse')) {
            $workPlate->detail->delete();
        }
        $workPlate->delete();

        return response()->noContent();
    }
}
