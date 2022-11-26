<?php

namespace App\Entity\Shop\PaymentMethod;

enum TypeEnum: string
{
    case AUTOMATIC = "automatic";
    case MANUAL = "manual";
}
