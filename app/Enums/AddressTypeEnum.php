<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class AddressTypeEnum extends Enum
{
    const Province = 3;
    const District = 2;
    const Ward = 1;
}
