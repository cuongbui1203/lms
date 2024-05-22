<?php

namespace App\Http\Requests\Statistical;

use App\Enums\StatusEnum;
use App\Http\Requests\SetTimeRequest;
use Illuminate\Validation\Rule;

class GetTotalOrderRequest extends SetTimeRequest
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
            'status' => [
                Rule::in([
                    StatusEnum::FAIL, // that bai
                    StatusEnum::RETURN , //tra ve
                    StatusEnum::COMPLETE, // hoan thanh
                    StatusEnum::R_DELIVERY, // dang van chuyen
                    StatusEnum::CREATE, // tao moi
                ]),
            ],
        ]);
    }
}
