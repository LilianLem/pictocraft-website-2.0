<?php

namespace App\Entity\Core\Division;

// Les noms des rôles correspondants sont prioritaires sur ces valeurs par défaut
enum RoleEnum: string
{
    case OFFICER = "Dirigeant";
    case MANAGER = "Gérant";
    case ASSISTANT = "Assistant";
}
