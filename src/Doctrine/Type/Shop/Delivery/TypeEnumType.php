<?php

namespace App\Doctrine\Type\Shop\Delivery;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\Delivery\TypeEnum;

class TypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return TypeEnum::class;
    }

    public function getName(): string
    {
        return "delivery_type_enum";
    }
}