<?php

namespace App\Entity\Shop\GameKey;

enum GameKeyDestinationEnum: string
{
    case RANDOM = "random";
    case YOGSCAST = "yogscast";
    case OTHER = "other";
}
