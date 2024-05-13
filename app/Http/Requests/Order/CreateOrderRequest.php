<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\FormRequest;
use App\Rules\CheckAddressValid;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
            'sender_name' => 'required',
            'receiver_name' => 'required',
            'sender_phone' => 'required',
            'receiver_phone' => 'required',
            'sender_address_id' => ['required', 'string', new CheckAddressValid()],
            'receiver_address_id' => ['required', 'string', new CheckAddressValid()],
            'sender_address' => ['nullable', 'string'],
            'receiver_address' => ['nullable', 'string'],
            'type_id' => ['required', Rule::in([
                config('type.goods.fragile'),
                config('type.goods.hazardous'),
                config('type.goods.normal'),
                config('type.goods.oversized'),
            ])],
        ];
    }
}
