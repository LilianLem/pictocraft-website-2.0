<?php

namespace App\Entity\Core\Division;

enum RoleEnum: string
{
    case OFFICER = "Dirigeant";
    case MANAGER = "Gérant";
    case ASSISTANT = "Assistant";
}
