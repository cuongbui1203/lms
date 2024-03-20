<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use Password;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string',
            'dob' => 'date',
            'image' => 'image',
            'wp_id' => 'exists:work_plates,id',
            'email' => 'email',
        ];
    }
}