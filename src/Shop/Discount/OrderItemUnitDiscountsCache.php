<?php

namespace App\Shop\Discount;

use Exception;

class OrderItemUnitDiscountsCache
{
    public function __construct(private int $discountCalculationBasis, private int $discountedAmount = 0)
    {
    }

    public function getDiscountCalculationBasis(): int
    {
        return $this->discountCalculationBasis;
    }

    public function setDiscountCalculationBasis(int $discountCalculationBasis, bool $deductFromPreviousAmount = true): self
    {
        if($discountCalculationBasis < 0) {
            throw new Exception("Impossible d'avoir une base de calcul négative !");
        }

        $this->discountCalculationBasis = $discountCalculationBasis - ($deductFromPreviousAmount ? $this->discountCalculationBasis : 0);

        return $this;
    }

    public function getDiscountedAmount(): int
    {
        return $this->discountedAmount;
    }

    public function setDiscountedAmount(int $discountedAmount, bool $addUpToPreviousAmount = true): self
    {
        if($discountedAmount < 0) {
            throw new Exception($addUpToPreviousAmount ? "Impossible d'appliquer un montant négatif au cache des réductions !" : "Impossible d'avoir un montant de réductions négatif !");
        }

        $this->discountedAmount = $discountedAmount + ($addUpToPreviousAmount ? $this->discountedAmount : 0);

        return $this;
    }
}