<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\GetListRequest;

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
