<?php

namespace App\Http\Requests\WorkPlate;

use App\Http\Requests\FormRequest;
use App\Rules\VungRule;

class GetSuggestionWPRequest extends FormRequest
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
            'address_id' => ['required', new VungRule()],
        ];
    }
}
