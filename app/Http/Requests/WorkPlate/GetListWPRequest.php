<?php

namespace App\Http\Requests\WorkPlate;

use App\Enums\RoleEnum;
use App\Http\Requests\GetListRequest;
use Illuminate\Validation\Rule;

class GetListWPRequest extends GetListRequest
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
        return array_merge(parent::rules(), [
            'type_id' => [
                Rule::in([
                    config('type.workPlate.transactionPoint'),
                    config('type.workPlate.transshipmentPoint'),
                    config('type.workPlate.warehouse'),
                ]),
            ],
        ]);
    }
}
