<?php

namespace App\Entity\Shop;

enum GameKeyDestinationEnum: string
{
    case RANDOM = "random";
    case YOGSCAST = "yogscast";
    case OTHER = "other";
}
