<?php

namespace App\Entity\Core;

use App\Entity\External\Steam\SteamGame;
use App\Repository\Core\UserSteamGameRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSteamGameRepository::class)]
class UserSteamGame
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SteamGame $game = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'steamGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?DateTimeImmutable $addedAt = null;

    public function getGame(): ?SteamGame
    {
        return $this->game;
    }

    public function setGame(?SteamGame $game): self
    {
        $this->game = $game;

        return $this;
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

    public function getAddedAt(): ?DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }
}
