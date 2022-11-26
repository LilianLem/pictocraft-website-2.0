<?php

namespace App\Doctrine\Type\Shop\OrderItem;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\OrderItem\StatusEnum;

class StatusEnumType extends EnumType
{
    public function getEnum(): string
    {
        return StatusEnum::class;
    }

    public function getName(): string
    {
        return "order_item_status_enum";
    }
}