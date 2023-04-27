<?php

namespace App\Entity\Shop;

use App\Entity\Shop\Discount\AppliedDiscount;
use Doctrine\Common\Collections\Collection;

interface DiscountableEntityInterface
{
    public function getTotalAmountHt(): ?int;

    public function getTotalAmountTtc(): ?int;

    /** @return Collection<int, AppliedDiscount> */
    public function getAppliedDiscounts(): Collection;

    public function addAppliedDiscount(AppliedDiscount $appliedDiscount): self;

    public function removeAppliedDiscount(AppliedDiscount $appliedDiscount): self;
}