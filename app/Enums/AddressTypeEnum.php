<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class AddressTypeEnum extends Enum
{
    const Province = 3;
    const District = 1;
    const Ward = 2;
}
