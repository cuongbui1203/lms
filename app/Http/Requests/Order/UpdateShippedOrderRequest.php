<?php

namespace App\Http\Requests\Order;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippedOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user && (in_array($user->role_id, [
            RoleEnum::ADMIN,
            RoleEnum::SHIPPER,
        ]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    StatusEnum::COMPLETE,
                    StatusEnum::FAIL,
                    StatusEnum::RETURN,
                ]),
            ],
        ];
    }
}
