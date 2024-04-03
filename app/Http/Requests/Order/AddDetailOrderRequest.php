<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class AddDetailOrderRequest extends FormRequest
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
            'type_id' => [
                'required',
                Rule::in([
                    config('type.goods.fragile'),
                    config('type.goods.normal'),
                    config('type.goods.oversized'),
                    config('type.goods.hazardous'),
                ]),
            ],
            'name' => 'required|string',
            'mass' => 'required|numeric|min:1',
            'desc' => 'required',
            'img' => 'image',
        ];
    }
}
