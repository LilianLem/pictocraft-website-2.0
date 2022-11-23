<?php

namespace App\Entity\Shop\AutomaticDelivery;

enum AutomaticDeliveryResult: int
{
    case SUCCESS = 1;
    case PARTIAL_SUCCESS = 2;
    case FAIL = 3;
}
