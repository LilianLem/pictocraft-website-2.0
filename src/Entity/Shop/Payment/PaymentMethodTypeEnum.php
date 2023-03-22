<?php

namespace App\Entity\Shop\Payment;

enum PaymentMethodTypeEnum: string
{
    case AUTOMATIC = "automatic";
    case MANUAL = "manual";
}
