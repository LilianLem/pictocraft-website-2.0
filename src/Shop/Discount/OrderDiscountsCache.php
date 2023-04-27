<?php

namespace App\Shop\Discount;

use App\Entity\Shop\Product;
use Exception;

class OrderDiscountsCache
{
    /** @param OrderItemDiscountsCache[] $itemsCacheCollection */
    public function __construct(private ?int $orderDiscountCalculationBasis = null, private array $itemsCacheCollection = [])
    {
    }

    public function getOrderDiscountCalculationBasis(): ?int
    {
        return $this->orderDiscountCalculationBasis;
    }

    public function setOrderDiscountCalculationBasis(int $orderDiscountCalculationBasis, bool $deductFromPreviousAmount = true): self
    {
        if($orderDiscountCalculationBasis < 0) {
            throw new Exception("Impossible d'avoir une base de calcul négative !");
        }

        $this->orderDiscountCalculationBasis = $orderDiscountCalculationBasis - ($deductFromPreviousAmount && $orderDiscountCalculationBasis ? $this->orderDiscountCalculationBasis : 0);

        return $this;
    }

    /** @return OrderItemDiscountsCache[] */
    public function getItemsCacheCollection(): array
    {
        return $this->itemsCacheCollection;
    }

    public function getItemCache(Product $product): OrderItemDiscountsCache
    {
        return $this->itemsCacheCollection[$product->getId()] ?? new OrderItemDiscountsCache();
    }

    public function setItemCache(Product $product, OrderItemDiscountsCache $itemCache): self
    {
        $this->itemsCacheCollection[$product->getId()] = $itemCache;

        return $this;
    }
}