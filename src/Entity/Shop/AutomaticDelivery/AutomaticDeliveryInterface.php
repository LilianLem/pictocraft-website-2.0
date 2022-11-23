<?php

namespace App\Entity\Shop\AutomaticDelivery;

use App\Entity\Core\User;

interface AutomaticDeliveryInterface
{
    public static function activateProduct(User $user): AutomaticDeliveryReturnType;
}