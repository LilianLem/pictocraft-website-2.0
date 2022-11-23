<?php

namespace App\Entity\Shop;

use App\Repository\Shop\AppliedDiscountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppliedDiscountRepository::class)]
#[ORM\Table(name: 'shop_applied_discount')]
class AppliedDiscount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appliedDiscounts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Discount $discount = null;

    #[ORM\ManyToOne(inversedBy: 'appliedDiscounts')]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'appliedDiscounts')]
    private ?OrderItem $orderItem = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La réduction fixe doit être supérieure à 0€. S'il s'agit d'un pourcentage de réduction, laissez ce champ vide")]
    private ?int $fixedDiscount = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "Le pourcentage de réduction doit être supérieur à 0%. S'il s'agit d'une réduction fixe, laissez ce champ vide")]
    #[Assert\LessThanOrEqual(100, message: "Le pourcentage de réduction ne peut pas dépasser 100%")]
    private ?int $percentageDiscount = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive(message: "Le montant de la réduction doit être supérieur à 0€")]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItem $orderItem): self
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    public function getFixedDiscount(): ?int
    {
        return $this->fixedDiscount;
    }

    public function setFixedDiscount(?int $fixedDiscount): self
    {
        $this->fixedDiscount = $fixedDiscount;

        return $this;
    }

    public function getPercentageDiscount(): ?int
    {
        return $this->percentageDiscount;
    }

    public function setPercentageDiscount(?int $percentageDiscount): self
    {
        $this->percentageDiscount = $percentageDiscount;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
