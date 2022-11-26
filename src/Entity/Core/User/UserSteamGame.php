<?php

namespace App\Entity\Core\User;

use App\Entity\External\Steam\SteamGame;
use App\Repository\Core\User\UserSteamGameRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserSteamGameRepository::class)]
#[ORM\UniqueConstraint("user_steam_game_unique", columns: ["game_id", "user_id"])]
#[UniqueEntity(
    fields: ["game", "user"],
    errorPath: "game",
    message: "Cet utilisateur possède déjà ce jeu dans sa bibliothèque",
)]
class UserSteamGame
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?SteamGame $game = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'steamGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Column]
    #[Assert\DateTime]
    #[Assert\NotBlank]
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
