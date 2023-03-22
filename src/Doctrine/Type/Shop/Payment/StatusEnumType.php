<?php

namespace App\Doctrine\Type\Shop\Payment;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\Payment\StatusEnum;

class StatusEnumType extends EnumType
{
    public function getEnum(): string
    {
        return StatusEnum::class;
    }

    public function getName(): string
    {
        return "payment_status_enum";
    }
}