<?php

namespace App\Http\Requests\WorkPlate;

use App\Http\Requests\GetListRequest;

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
        return array_merge(parent::rules(), [
            'sended' => 'boolean',
            'done' => 'boolean',
        ]);
    }
}
