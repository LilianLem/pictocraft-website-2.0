<?php

namespace App\Doctrine\Type\Shop;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\OrderItem\OrderItemStatusEnum;

class OrderItemStatusEnumType extends EnumType
{
    public function getEnum(): string
    {
        return OrderItemStatusEnum::class;
    }

    public function getName(): string
    {
        return "order_item_status_enum";
    }
}