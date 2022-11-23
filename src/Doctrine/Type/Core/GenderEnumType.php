<?php

namespace App\Doctrine\Type\Core;

use App\Doctrine\Type\EnumType;
use App\Entity\Core\GenderEnum;

class GenderEnumType extends EnumType
{
    public function getEnum(): string
    {
        return GenderEnum::class;
    }

    public function getName(): string
    {
        return "gender_enum";
    }
}