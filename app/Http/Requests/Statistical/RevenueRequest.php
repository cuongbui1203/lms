<?php

namespace App\Http\Requests\Statistical;

use App\Http\Requests\SetTimeRequest;

class RevenueRequest extends SetTimeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
