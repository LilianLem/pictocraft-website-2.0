<?php

namespace App\Doctrine\Type\Shop;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\GameKeyDestinationEnum;

class GameKeyDestinationEnumType extends EnumType
{
    public function getEnum(): string
    {
        return GameKeyDestinationEnum::class;
    }

    public function getName(): string
    {
        return "game_key_destination_enum";
    }
}