<?php

namespace App\Entity\Core;

use App\Repository\Core\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(exactly: 32)]
    private ?string $minecraftUuid = null;

    #[ORM\Column(length: 17, nullable: true)]
    #[Assert\Length(exactly: 17)]
    #[Assert\Regex('/\d+/')]
    private ?string $steamId = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(min: 17, max: 32)]
    #[Assert\Regex('/\d+/')]
    private ?string $discordId = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(min: 4, max: 255, minMessage: "La description doit comporter au moins {{ limit }} caractères", maxMessage: "La description doit comporter au maximum {{ limit }} caractères")]
    private ?string $description = null;

    // TODO : à changer en relation
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $games = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMinecraftUuid(): ?string
    {
        return $this->minecraftUuid;
    }

    public function setMinecraftUuid(?string $minecraftUuid): self
    {
        $this->minecraftUuid = $minecraftUuid;

        return $this;
    }

    public function getSteamId(): ?string
    {
        return $this->steamId;
    }

    public function setSteamId(?string $steamId): self
    {
        $this->steamId = $steamId;

        return $this;
    }

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(?string $discordId): self
    {
        $this->discordId = $discordId;

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

    public function getGames(): ?string
    {
        return $this->games;
    }

    public function setGames(?string $games): self
    {
        $this->games = $games;

        return $this;
    }
}
