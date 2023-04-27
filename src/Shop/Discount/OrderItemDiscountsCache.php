<?php

namespace App\Shop\Discount;

class OrderItemDiscountsCache
{
    private int $totalAmount = 0;

    /**
     * @param array<int, OrderItemUnitDiscountsCache> $cachePerUnit
     */
    public function __construct(private array $cachePerUnit = [])
    {
        if($cachePerUnit) {
            $this->updateDiscountedAmountsTotal();
        }
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    private function updateDiscountedAmountsTotal(): self
    {
        $this->totalAmount = array_reduce(
            $this->cachePerUnit, fn(int $total, OrderItemUnitDiscountsCache $cache) => $total + $cache->getDiscountedAmount(), 0
        );

        return $this;
    }

    public function getCachePerUnit(): array
    {
        return $this->cachePerUnit;
    }

    /** @var array<int, OrderItemUnitDiscountsCache> $cachePerUnit */
    public function setCachePerUnit(array $cachePerUnit): self
    {
        $this->cachePerUnit = $cachePerUnit;
        return $this->updateDiscountedAmountsTotal();
    }

    /**
     * @return array<int, int>
     */
    public function getDiscountCalculationBasisPerUnit(): array
    {
        /** @var array<int, int> $discountCalculationBasisPerUnit */
        $discountCalculationBasisPerUnit = [];

        foreach($this->cachePerUnit as $index => $cache) {
            $discountCalculationBasisPerUnit[$index] = $cache->getDiscountCalculationBasis();
        }

        return $discountCalculationBasisPerUnit;
    }

    /**
     * @return array<int, int>
     */
    public function getDiscountedAmountPerUnit(): array
    {
        /** @var array<int, int> $discountedAmountPerUnit */
        $discountedAmountPerUnit = [];

        foreach($this->cachePerUnit as $index => $cache) {
            $discountedAmountPerUnit[$index] = $cache->getDiscountedAmount();
        }

        return $discountedAmountPerUnit;
    }
}