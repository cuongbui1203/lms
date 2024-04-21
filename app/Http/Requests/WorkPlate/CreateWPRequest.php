<?php

namespace App\Http\Requests\WorkPlate;

use App\Enums\AddressTypeEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class CreateWPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role_id === RoleEnum::ADMIN;
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
            'address_id' => ['required', Rule::exists('sqlite_vn_map.wards', 'code')],
            'type_id' => [
                'required',
                Rule::in(
                    config('type.workPlate.transactionPoint'),
                    config('type.workPlate.warehouse'),
                    config('type.workPlate.transshipmentPoint'),
                ),
            ],
            'cap' => ['required', Rule::in(AddressTypeEnum::getValues())],
            'max_payload' => 'numeric',

        ];
    }
}
