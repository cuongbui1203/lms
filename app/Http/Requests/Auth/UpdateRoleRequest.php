<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user ? $user->role_id === RoleEnum::ADMIN : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roleIdNew' => [
                'required',
                Rule::in(RoleEnum::getValues()),
            ],
        ];
    }
}
