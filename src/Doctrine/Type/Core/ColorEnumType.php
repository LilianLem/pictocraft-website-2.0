<?php

namespace App\Doctrine\Type\Core;

use App\Doctrine\Type\EnumType;
use App\Entity\Core\ColorEnum;

class ColorEnumType extends EnumType
{
    public function getEnum(): string
    {
        return ColorEnum::class;
    }

    public function getName(): string
    {
        return "color_enum";
    }
}