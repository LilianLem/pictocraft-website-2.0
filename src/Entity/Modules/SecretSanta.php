<?php

namespace App\Entity\Modules;

use App\Entity\Core\User\User;
use App\Repository\Modules\SecretSantaRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SecretSantaRepository::class)]
class SecretSanta
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'secretSantaData', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeImmutable $registeredAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $giftingTo = null;

    #[ORM\Column(nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeImmutable $requestedAddressAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $addressRequestAnswer = null;

    #[ORM\Column(nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeImmutable $addressRequestAnswerAt = null;

    #[ORM\Column(nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeImmutable $sawAddressAt = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Length(max: 150, maxMessage: "Le message en cadeau ne peut pas dépasser {{ limit }} caractères")]
    private ?string $giftMessage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $giftMessageLastUpdatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sentPickupLocation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $informedDelivery = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRegisteredAt(): ?DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getGiftingTo(): ?User
    {
        return $this->giftingTo;
    }

    public function setGiftingTo(?User $giftingTo): self
    {
        $this->giftingTo = $giftingTo;

        return $this;
    }

    public function getRequestedAddressAt(): ?DateTimeImmutable
    {
        return $this->requestedAddressAt;
    }

    public function setRequestedAddressAt(?DateTimeImmutable $requestedAddressAt): self
    {
        $this->requestedAddressAt = $requestedAddressAt;

        return $this;
    }

    public function isAddressRequestAnswer(): ?bool
    {
        return $this->addressRequestAnswer;
    }

    public function setAddressRequestAnswer(?bool $addressRequestAnswer): self
    {
        $this->addressRequestAnswer = $addressRequestAnswer;

        return $this;
    }

    public function getAddressRequestAnswerAt(): ?DateTimeImmutable
    {
        return $this->addressRequestAnswerAt;
    }

    public function setAddressRequestAnswerAt(?DateTimeImmutable $addressRequestAnswerAt): self
    {
        $this->addressRequestAnswerAt = $addressRequestAnswerAt;

        return $this;
    }

    public function getSawAddressAt(): ?DateTimeImmutable
    {
        return $this->sawAddressAt;
    }

    public function setSawAddressAt(?DateTimeImmutable $sawAddressAt): self
    {
        $this->sawAddressAt = $sawAddressAt;

        return $this;
    }

    public function getGiftMessage(): ?string
    {
        return $this->giftMessage;
    }

    public function setGiftMessage(?string $giftMessage): self
    {
        $this->giftMessage = $giftMessage;

        return $this;
    }

    public function getGiftMessageLastUpdatedAt(): ?DateTimeInterface
    {
        return $this->giftMessageLastUpdatedAt;
    }

    public function setGiftMessageLastUpdatedAt(?DateTimeInterface $giftMessageLastUpdatedAt): self
    {
        $this->giftMessageLastUpdatedAt = $giftMessageLastUpdatedAt;

        return $this;
    }

    public function isSentPickupLocation(): ?bool
    {
        return $this->sentPickupLocation;
    }

    public function setSentPickupLocation(?bool $sentPickupLocation): self
    {
        $this->sentPickupLocation = $sentPickupLocation;

        return $this;
    }

    public function isInformedDelivery(): ?bool
    {
        return $this->informedDelivery;
    }

    public function setInformedDelivery(?bool $informedDelivery): self
    {
        $this->informedDelivery = $informedDelivery;

        return $this;
    }
}
