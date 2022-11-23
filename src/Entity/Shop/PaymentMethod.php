<?php

namespace App\Entity\Shop;

use App\Repository\Shop\PaymentMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
#[ORM\Table(name: 'shop_payment_method')]
class PaymentMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    #[Assert\Length(min: 3, max: 32, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: "payment_method_type_enum")]
    #[Assert\NotBlank]
    private ?PaymentMethodTypeEnum $type = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $enabled = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $selectable = null;

    #[ORM\OneToMany(mappedBy: 'paymentMethod', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?PaymentMethodTypeEnum
    {
        return $this->type;
    }

    public function setType(PaymentMethodTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isSelectable(): ?bool
    {
        return $this->selectable;
    }

    public function setSelectable(bool $selectable): self
    {
        $this->selectable = $selectable;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setPaymentMethod($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPaymentMethod() === $this) {
                $order->setPaymentMethod(null);
            }
        }

        return $this;
    }
}
