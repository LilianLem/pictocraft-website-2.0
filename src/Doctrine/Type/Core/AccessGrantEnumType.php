<?php

namespace App\Doctrine\Type\Core;

use App\Doctrine\Type\EnumType;
use App\Entity\Core\AccessGrantEnum;

class AccessGrantEnumType extends EnumType
{
    public function getEnum(): string
    {
        return AccessGrantEnum::class;
    }

    public function getName(): string
    {
        return "access_grant_enum";
    }
}