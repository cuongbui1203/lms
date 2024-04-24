<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'dob' => 'date',
            'username' => 'required|unique:users,username',
            'address_id' => 'required|exists:sqlite_vn_map.wards,code',
            'address' => 'required|string|nullable',
            'role_id' => [
                Rule::in([
                    RoleEnum::DRIVER,
                    RoleEnum::EMPLOYEE,
                    RoleEnum::MANAGER,
                    RoleEnum::SHIPPER,
                ]),
            ],
            'wp_id' => 'exists:work_plates,id',
            'img' => 'image',
        ];
    }
}
