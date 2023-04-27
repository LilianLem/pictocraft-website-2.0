<?php

namespace App\Entity\Shop\Discount;

use App\Repository\Shop\Discount\DiscountForbiddenCombinationRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscountForbiddenCombinationRepository::class)]
#[ORM\Table(name: 'shop_discount_forbidden_combination')]
#[ORM\UniqueConstraint("discount_forbidden_combination_unique", columns: ["discount1_id", "discount2_id"])]
#[UniqueEntity(
    fields: ["discount1", "discount2"],
    errorPath: "discount2",
    ignoreNull: true,
    message: "Cette combinaison existe déjà",
)]
class ForbiddenCombination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'forbiddenCombinations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Discount $discount1 = null;

    #[ORM\ManyToOne(inversedBy: 'forbiddenCombinations2')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Discount $discount2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return int[] */
    public function getDiscountIds(): array
    {
        $discountIds = [];

        if($this->getDiscount1()) $discountIds[] = $this->getDiscount1()->getId();
        if($this->getDiscount2()) $discountIds[] = $this->getDiscount2()->getId();

        return $discountIds;
    }

    public function getDiscount1(): ?Discount
    {
        return $this->discount1;
    }

    public function setDiscount1(?Discount $discount1): self
    {
        if($discount1 && $this->getDiscount2()) {
            if($discount1->getId() === $this->getDiscount2()->getId()) {
                throw new Exception("La réduction est déjà présente dans cette combinaison");
            } elseif($discount1->getId() > $this->getDiscount2()->getId()) {
                $discount2 = $this->getDiscount2();
                $this->discount2 = $discount1;
                $discount1 = $discount2;
            }
        }

        $this->discount1 = $discount1;

        return $this;
    }

    public function getDiscount2(): ?Discount
    {
        return $this->discount2;
    }

    public function setDiscount2(?Discount $discount2): self
    {
        if($discount2 && $this->getDiscount1()) {
            if($discount2->getId() === $this->getDiscount1()->getId()) {
                throw new Exception("La réduction est déjà présente dans cette combinaison");
            } elseif($discount2->getId() < $this->getDiscount1()->getId()) {
                $discount1 = $this->getDiscount1();
                $this->discount1 = $discount2;
                $discount2 = $discount1;
            }
        }

        $this->discount2 = $discount2;

        return $this;
    }
}
