<?php

namespace App\Doctrine\Type\Core;

use App\Doctrine\Type\EnumType;
use App\Entity\Core\DivisionRoleEnum;

class DivisionRoleEnumType extends EnumType
{
    public function getEnum(): string
    {
        return DivisionRoleEnum::class;
    }

    public function getName(): string
    {
        return "division_role_enum";
    }
}