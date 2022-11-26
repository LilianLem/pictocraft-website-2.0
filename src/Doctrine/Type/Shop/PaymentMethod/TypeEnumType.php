<?php

namespace App\Doctrine\Type\Shop\PaymentMethod;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\PaymentMethod\TypeEnum;

class TypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return TypeEnum::class;
    }

    public function getName(): string
    {
        return "payment_method_type_enum";
    }
}