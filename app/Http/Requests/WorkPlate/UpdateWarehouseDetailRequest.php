<?php

namespace App\Http\Requests\WorkPlate;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Auth;

class UpdateWarehouseDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user->role_id === RoleEnum::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'max_payload' => 'required|numeric',
        ];
    }
}
