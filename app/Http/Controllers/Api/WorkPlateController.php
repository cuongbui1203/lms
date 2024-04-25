<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetListWPRequest;
use App\Http\Requests\WorkPlate\CreateWPRequest;
use App\Http\Requests\WorkPlate\GetSuggestionWPRequest;
use App\Http\Requests\WorkPlate\UpdateWarehouseDetailRequest;
use App\Http\Requests\WorkPlate\UpdateWPRequest;
use App\Models\WorkPlate;
use Illuminate\Support\Collection;

class WorkPlateController extends Controller
{
    public function index(GetListWPRequest $request)
    {
        $wp = WorkPlate::query();
        if ($request->type_id) {
            $wp->where('type_id', '=', $request->type_id);
        }

        $wp = $wp->get(['id', 'name', 'address_id', 'cap', 'created_at', 'updated_at', 'type_id']);
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
        $workPlate->address_id = [$request->address_id, $request->address];
        $workPlate->type_id = $request->type_id;
        $workPlate->cap = $request->cap;
        $workPlate->vung = getAddressCode($request->address_id, $request->cap);
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
    public function update(UpdateWPRequest $request, WorkPlate $workPlate) //phpcs:ignore
    {
        $workPlate->name = $request->name ?? $workPlate->name;
        if ($request->address_id) {
            $workPlate->address_id = [$request->address_id, $workPlate->address->address];
        }

        if ($request->address) {
            $workPlate->address_id = [$workPlate->address->wardCode, $request->address];
        }

        $workPlate->address_id = [$request->address_id ?? $request->address_id, $request->address];
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

        if ($request->cap) {
            $workPlate->cap = $request->cap;
            $workPlate->vung = getAddressCode($workPlate->address->wardCode, $request->cap);
        }

        $workPlate->save();
        $workPlate->load('type');

        return $this->sendSuccess(WorkPlate::find($workPlate->id));
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

    public function getSuggestionWP(GetSuggestionWPRequest $request)
    {
        $res = new Collection();
        $addressCode = [$request->address_id];
        array_push($addressCode, getAddressCode($request->address_id, AddressTypeEnum::DISTRICT));
        array_push($addressCode, getAddressCode($request->address_id, AddressTypeEnum::PROVINCE));
        $res->push(WorkPlate::whereIn('vung', $addressCode)
            ->get(['id', 'name', 'address_id', 'created_at', 'updated_at', 'type_id'])
            ->load('type', 'detail'));

        return $this->sendSuccess($res->first()->toArray(), 'get suggestion wp success');
    }
}
