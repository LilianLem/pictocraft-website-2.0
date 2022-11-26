<?php

namespace App\Doctrine\Type\Shop\GameKey;

use App\Doctrine\Type\EnumType;
use App\Entity\Shop\GameKey\DestinationEnum;

class DestinationEnumType extends EnumType
{
    public function getEnum(): string
    {
        return DestinationEnum::class;
    }

    public function getName(): string
    {
        return "game_key_destination_enum";
    }
}