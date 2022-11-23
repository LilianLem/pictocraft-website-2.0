<?php

namespace App\Doctrine\Type\Shop;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\OrderStatusEnum;

class OrderStatusEnumType extends EnumType
{
    public function getEnum(): string
    {
        return OrderStatusEnum::class;
    }

    public function getName(): string
    {
        return "order_status_enum";
    }
}