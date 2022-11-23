<?php

namespace App\Doctrine\Type\Shop;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\GameKeyTypeEnum;

class GameKeyTypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return GameKeyTypeEnum::class;
    }

    public function getName(): string
    {
        return "game_key_type_enum";
    }
}