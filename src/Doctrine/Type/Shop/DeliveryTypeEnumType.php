<?php

namespace App\Doctrine\Type\Shop;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\DeliveryTypeEnum;

class DeliveryTypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return DeliveryTypeEnum::class;
    }

    public function getName(): string
    {
        return "delivery_type_enum";
    }
}