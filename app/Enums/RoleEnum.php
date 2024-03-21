<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RoleEnum extends Enum
{
    const Admin = 1;
    const User = 2;
    const Driver = 3;
    const Employee = 4;
    const Manager = 5;
}
