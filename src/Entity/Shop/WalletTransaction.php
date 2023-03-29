<?php

namespace App\Entity\Shop;

use App\Entity\Core\User\User;
use App\Entity\Shop\Discount\Discount;
use App\Repository\Shop\WalletTransactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WalletTransactionRepository::class)]
#[ORM\Table(name: 'shop_wallet_transaction')]
class WalletTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'walletTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotEqualTo(0, message: "Le montant de la transaction ne peut pas être égal à 0€")]
    private ?int $amount = null;

    #[ORM\Column(options: ["unsigned" => true, "default" => 0])]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero(message: "Le solde ne peut pas être inférieur à 0€")]
    private ?int $balance = null;

    #[ORM\Column]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $date = null;

    #[ORM\OneToOne(inversedBy: 'walletTransaction', cascade: ['persist', 'remove'])]
    private ?Discount $generatedDiscount = null;

    public function __construct()
    {
        $this->balance = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getGeneratedDiscount(): ?Discount
    {
        return $this->generatedDiscount;
    }

    public function setGeneratedDiscount(?Discount $generatedDiscount): self
    {
        $this->generatedDiscount = $generatedDiscount;

        return $this;
    }
}
