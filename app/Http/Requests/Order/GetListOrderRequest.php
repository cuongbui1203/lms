<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\GetListRequest;
use App\Rules\StatusesArrayRule;
use Illuminate\Validation\Rule;

class GetListOrderRequest extends GetListRequest
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
        // dd($this->all());
        return array_merge(
            parent::rules(),
            [
                'statuses' => ['required', 'json', new StatusesArrayRule()],
            ]
        );
    }
}
