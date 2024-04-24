<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\FormRequest;
use App\Rules\VungRule;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $id = (int) getLastSegmentRegex(request()->getPathInfo());

        return $user && ($user->role_id === RoleEnum::ADMIN || $user->id === $id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'dob' => 'date',
            'image' => 'image',
            'email' => 'email|unique:users,email',
            'phone' => 'string',
            'address_id' => [
                'string',
                new VungRule(),
            ],
            'address' => 'string|nullable',
            'role_id' => [
                Rule::in(RoleEnum::getValues()),
            ],
        ];
    }
}
