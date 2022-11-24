<?php

namespace App\Entity\Shop\Discount;

enum DiscountAppliesOnEnum: string
{
    case ORDER = "order";
    case PRODUCT = "product";
}
