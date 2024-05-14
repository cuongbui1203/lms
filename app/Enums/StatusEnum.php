<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatusEnum extends Enum
{
    public const WAIT_F_DELIVERY = 1;
    public const R_DELIVERY = 2;
    public const DONE = 3;

    public const LEAVE_TRANSPORT_POINT = 4;
    public const LEAVE_TRANSACTION_POINT = 14;

    public const AT_TRANSPORT_POINT = 5;
    public const AT_TRANSACTION_POINT = 13;

    public const SHIPPING = 6;

    public const TO_THE_TRANSPORT_POINT = 7;
    public const TO_THE_TRANSACTION_POINT = 8;

    public const CREATE = 10;
    public const RETURN  = 9;
    public const COMPLETE = 11;
    public const FAIL = 12;
}
