<?php

namespace App\Doctrine\Type\Shop\GameKey;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\GameKey\TypeEnum;

class TypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return TypeEnum::class;
    }

    public function getName(): string
    {
        return "game_key_type_enum";
    }
}