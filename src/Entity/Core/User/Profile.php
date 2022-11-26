<?php

namespace App\Entity\Core\User;

use App\Repository\Core\User\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'user_profile')]
#[UniqueEntity("minecraftUuid", message: "Ce compte Minecraft est déjà relié à un utilisateur")]
#[UniqueEntity("steamId", message: "Ce compte Steam est déjà relié à un utilisateur")]
#[UniqueEntity("discordId", message: "Ce compte Discord est déjà relié à un utilisateur")]
class Profile
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 32, unique: true, nullable: true)]
    #[Assert\Length(exactly: 32, exactMessage: "L'UUID Minecraft doit comporter {{ limit }} caractères")]
    #[Assert\Regex('/[\da-f]+/', message: "Le format de l'UUID Minecraft est incorrect")]
    private ?string $minecraftUuid = null;

    // TODO : pour une future version, permettre de relier plusieurs comptes Steam (notamment pour la vérification des jeux déjà possédés)
    #[ORM\Column(length: 17, unique: true, nullable: true)]
    #[Assert\Length(exactly: 17, exactMessage: "L'ID Steam doit comporter {{ limit }} caractères")]
    #[Assert\Regex('/\d+/', message: "L'ID Steam doit être numérique")]
    private ?string $steamId = null;

    #[ORM\Column(length: 32, unique: true, nullable: true)]
    #[Assert\Length(min: 17, max: 32, minMessage: "L'ID Discord doit comporter au moins {{ limit }} caractères", maxMessage: "L'ID Discord ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/\d+/', message: "L'ID Discord doit être numérique")]
    private ?string $discordId = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(min: 4, max: 255, minMessage: "La description doit comporter au moins {{ limit }} caractères", maxMessage: "La description ne peut pas dépasser {{ limit }} caractères")]
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
