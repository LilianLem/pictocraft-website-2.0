<?php

namespace App\Entity\Shop;

enum DeliveryTypeEnum: string
{
    case AUTOMATIC = "automatic";
    case MANUAL_USER = "manual_user";
    case MANUAL_SHOP = "manual_shop";
}
