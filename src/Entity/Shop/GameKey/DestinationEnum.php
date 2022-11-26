<?php

namespace App\Entity\Shop\GameKey;

enum DestinationEnum: string
{
    case RANDOM = "random";
    case YOGSCAST = "yogscast";
    case OTHER = "other";
}
