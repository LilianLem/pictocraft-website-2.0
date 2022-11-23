<?php

namespace App\Entity\Core;

enum DivisionRoleEnum: string
{
    case OFFICER = "Dirigeant";
    case MANAGER = "Gérant";
    case ASSISTANT = "Assistant";
}
