<?php

namespace App\Http\Requests\Statistical;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
            'role' => [Rule::in([
                RoleEnum::DRIVER,
                RoleEnum::EMPLOYEE,
                RoleEnum::MANAGER,
                RoleEnum::SHIPPER,
            ])],
        ];
    }
}
