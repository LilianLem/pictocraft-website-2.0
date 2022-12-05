<?php

namespace App\Entity\Core;

enum AccessGrantEnum: string
{
    case STAFF_ACCEPTED_JOIN_REQUEST = "staff";
    case MEMBER_RECOMMENDATION = "member";
    case LEGACY = "legacy"; // À utiliser pour tous les comptes ayant rejoint avant la modification des conditions d'accès
}
