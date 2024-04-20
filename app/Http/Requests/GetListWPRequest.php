<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Validation\Rule;

class GetListWPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user*/
        $user = auth()->user();
        $accepts = [
            RoleEnum::ADMIN,
            RoleEnum::MANAGER,
            RoleEnum::EMPLOYEE,
        ];

        return in_array($user->role_id, $accepts);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_id' => [
                Rule::in([
                    config('type.workPlate.transactionPoint'),
                    config('type.workPlate.transshipmentPoint'),
                    config('type.workPlate.warehouse'),
                ]),
            ],
        ];
    }
}
