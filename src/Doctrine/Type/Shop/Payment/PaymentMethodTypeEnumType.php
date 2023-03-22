<?php

namespace App\Doctrine\Type\Shop\Payment;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\Payment\PaymentMethodTypeEnum;

class PaymentMethodTypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return PaymentMethodTypeEnum::class;
    }

    public function getName(): string
    {
        return "payment_method_type_enum";
    }
}