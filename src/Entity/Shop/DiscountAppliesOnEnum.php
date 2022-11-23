<?php

namespace App\Entity\Shop;

enum DiscountAppliesOnEnum: string
{
    case ORDER = "order";
    case PRODUCT = "product";
}
