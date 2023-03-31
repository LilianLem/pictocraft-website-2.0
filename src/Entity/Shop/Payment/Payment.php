<?php

namespace App\Entity\Shop\Payment;

use App\Entity\Shop\Order\Order;
use App\Repository\Shop\Payment\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Table(name: 'shop_payment')]
#[ORM\UniqueConstraint("payment_method_order_unique", columns: ["order_id", "payment_method_id"])]
#[UniqueEntity(
    fields: ["order", "paymentMethod"],
    errorPath: "paymentMethod",
    message: "Ce moyen de paiement est déjà actif sur cette commande.",
)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Positive(message: "Le montant à payer doit être supérieur à 0€")]
    #[Assert\NotBlank]
    private ?int $amount = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le montant remboursé doit être supérieur à 0€. Si aucun montant n'a été remboursé, laissez ce champ vide")]
    private ?int $refundedAmount = null;

    #[ORM\Column(length: 128, nullable: true)]
    #[Assert\Length(max: 128, maxMessage: "Le token de paiement ne doit pas dépasser {{ limit }} caractères")]
    private ?string $token = null;

    #[ORM\OneToMany(mappedBy: 'payment', targetEntity: Status::class, orphanRemoval: true)]
    private Collection $statusHistory;

    public function __construct()
    {
        $this->statusHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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

    public function getRefundedAmount(): ?int
    {
        return $this->refundedAmount;
    }

    public function setRefundedAmount(?int $refundedAmount): self
    {
        $this->refundedAmount = $refundedAmount;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, Status>
     */
    public function getStatusHistory(): Collection
    {
        return $this->statusHistory;
    }

    public function addStatusToHistory(Status $statusHistory): self
    {
        if (!$this->statusHistory->contains($statusHistory)) {
            $this->statusHistory->add($statusHistory);
            $statusHistory->setPayment($this);
        }

        return $this;
    }

    public function removeStatusFromHistory(Status $statusHistory): self
    {
        if ($this->statusHistory->removeElement($statusHistory)) {
            // set the owning side to null (unless already changed)
            if ($statusHistory->getPayment() === $this) {
                $statusHistory->setPayment(null);
            }
        }

        return $this;
    }
}
