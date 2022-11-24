<?php

namespace App\Entity\Core\User;

use App\Repository\Core\User\UserStatsRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatsRepository::class)]
class UserStats
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'stats', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastSteamCheckAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastLoginAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastRedeemedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastLoginAttemptAt = null;

    #[ORM\Column]
    private ?int $nbLoginAttempts = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $gifted = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastSteamCheckAt(): ?DateTimeInterface
    {
        return $this->lastSteamCheckAt;
    }

    public function setLastSteamCheckAt(?DateTimeInterface $lastSteamCheckAt): self
    {
        $this->lastSteamCheckAt = $lastSteamCheckAt;

        return $this;
    }

    public function getLastLoginAt(): ?DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getLastRedeemedAt(): ?DateTimeInterface
    {
        return $this->lastRedeemedAt;
    }

    public function setLastRedeemedAt(?DateTimeInterface $lastRedeemedAt): self
    {
        $this->lastRedeemedAt = $lastRedeemedAt;

        return $this;
    }

    public function getLastLoginAttemptAt(): ?DateTimeInterface
    {
        return $this->lastLoginAttemptAt;
    }

    public function setLastLoginAttemptAt(?DateTimeInterface $lastLoginAttemptAt): self
    {
        $this->lastLoginAttemptAt = $lastLoginAttemptAt;

        return $this;
    }

    public function getNbLoginAttempts(): ?int
    {
        return $this->nbLoginAttempts;
    }

    public function setNbLoginAttempts(int $nbLoginAttempts): self
    {
        $this->nbLoginAttempts = $nbLoginAttempts;

        return $this;
    }

    public function isGifted(): ?bool
    {
        return $this->gifted;
    }

    public function setGifted(bool $gifted): self
    {
        $this->gifted = $gifted;

        return $this;
    }
}
