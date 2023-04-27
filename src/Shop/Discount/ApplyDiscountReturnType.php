<?php

namespace App\Shop\Discount;

use Exception;

class ApplyDiscountReturnType
{
    public function __construct(public readonly bool $applied, public readonly OrderDiscountsCache $orderDiscountsCache, public readonly ?NotAppliedTypeEnum $notAppliedType = null, public readonly ?string $notAppliedMessage = null)
    {
        if(!$this->applied && (!$this->notAppliedMessage || !$this->notAppliedType)) {
            throw new Exception("Impossible de définir une réduction comme non appliquée sans message et type d'erreur");
        }
    }
}