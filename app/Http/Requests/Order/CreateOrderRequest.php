<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

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
            'sender_address_id' => 'required|exists:sqlite_vn_map.wards,code',
            'receiver_address_id' => 'required|exists:sqlite_vn_map.wards,code',
        ];
    }
}
