<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\FormRequest;
use App\Rules\VungRule;

class MoveOrderRequest extends FormRequest
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
            'from_address_id' => [
                'nullable',
                'string',
                new VungRule(),
            ],
            'to_address_id' => [
                'nullable',
                'string',
                new VungRule(),
            ],
            'from_id' => 'nullable|exists:work_plates:id',
            'to_id' => 'nullable|exists:work_plates:id',
            'description' => 'nullable|string',
        ];
    }
}
