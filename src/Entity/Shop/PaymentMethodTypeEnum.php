<?php

namespace App\Entity\Shop;

enum PaymentMethodTypeEnum: string
{
    case AUTOMATIC = "automatic";
    case MANUAL = "manual";
}
