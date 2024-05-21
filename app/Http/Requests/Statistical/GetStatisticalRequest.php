<?php

namespace App\Http\Requests\Statistical;

use App\Enums\StatusEnum;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class GetStatisticalRequest extends FormRequest
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
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'status' => [
                Rule::in([
                    StatusEnum::FAIL, // that bai
                    StatusEnum::RETURN , //tra ve
                    StatusEnum::COMPLETE, // hoan thanh
                    StatusEnum::R_DELIVERY, // dang van chuyen
                    StatusEnum::CREATE, // tao moi
                ]),
            ],
        ];
    }
}
