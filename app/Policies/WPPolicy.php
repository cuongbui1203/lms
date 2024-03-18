<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class WPPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->role_id === RoleEnum::Admin;
    }
}
