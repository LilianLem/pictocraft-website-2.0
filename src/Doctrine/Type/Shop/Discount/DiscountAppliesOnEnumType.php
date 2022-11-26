<?php

namespace App\Doctrine\Type\Shop\Discount;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\Discount\DiscountAppliesOnEnum;

class DiscountAppliesOnEnumType extends EnumType
{
    public function getEnum(): string
    {
        return DiscountAppliesOnEnum::class;
    }

    public function getName(): string
    {
        return "discount_applies_on_enum";
    }
}