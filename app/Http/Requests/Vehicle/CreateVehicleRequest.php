<?php

namespace App\Http\Requests\Vehicle;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

class CreateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role_id === RoleEnum::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'payload' => 'required|numeric|min:0',
            'typeId' => [
                'required',
                Rule::in([
                    config('type.vehicle.freezing'),
                    config('type.vehicle.truck'),
                    config('type.vehicle.container'),
                    config('type.vehicle.tractor'),
                ])
            ]
        ];
    }
}
