<?php

namespace App\Http\Requests\Vehicle;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role_id === RoleEnum::ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => Rule::in([
                config('type.vehicle.freezing'),
                config('type.vehicle.truck'),
                config('type.vehicle.container'),
                config('type.vehicle.tractor'),
            ]),
            'payload' => 'numerics|min:0',
            'driverId' => 'exists:users,id',
            'goodsType' => [
                Rule::in([
                    config('type.goods.fragile'),
                    config('type.goods.normal'),
                    config('type.goods.oversized'),
                    config('type.goods.hazardous'),
                ]),
            ],
        ];
    }
}
