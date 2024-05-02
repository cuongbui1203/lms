<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\GetListRequest;
use Illuminate\Validation\Rule;

class GetListAccountRequest extends GetListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user && ($user->role_id === RoleEnum::ADMIN || $user->role_id === RoleEnum::MANAGER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules2 = [
            'role_id' => Rule::in(RoleEnum::getValues()),
        ];

        return array_merge($rules, $rules2);
    }
}
