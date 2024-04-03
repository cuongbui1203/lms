<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RoleEnum extends Enum
{
    public const ADMIN = 1;
    public const USER = 2;
    public const DRIVER = 3;
    public const EMPLOYEE = 4;
    public const MANAGER = 5;
}
