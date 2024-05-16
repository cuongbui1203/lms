<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\FormRequest;
use App\Rules\OrderListIdRule;

class ListOrderIdRequest extends FormRequest
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
            'orders' => [
                'required',
                'json',
                new OrderListIdRule(),
            ],
        ];
    }

    /**
     * convert json string to array orderId
     *
     * @return array
     */
    public function getOrders()
    {
        return json_decode($this->get('orders'));
    }
}
