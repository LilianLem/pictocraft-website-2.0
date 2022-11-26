<?php

namespace App\Doctrine\Type\Core\Division;

use App\Doctrine\Type\EnumType;
use App\Entity\Core\Division\RoleEnum;

class RoleEnumType extends EnumType
{
    public function getEnum(): string
    {
        return RoleEnum::class;
    }

    public function getName(): string
    {
        return "division_role_enum";
    }
}