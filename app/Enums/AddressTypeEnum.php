<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AddressTypeEnum extends Enum
{
    public const PROVINCE = 3;
    public const DISTRICT = 2;
    public const WARD = 1;
}
