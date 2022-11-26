<?php

namespace App\Doctrine\Type\Shop\Order;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\Order\StatusEnum;

class StatusEnumType extends EnumType
{
    public function getEnum(): string
    {
        return StatusEnum::class;
    }

    public function getName(): string
    {
        return "order_status_enum";
    }
}