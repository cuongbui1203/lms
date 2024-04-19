<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;

class GetListUserRequest extends GetListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role_id === RoleEnum::ADMIN;
    }
}
