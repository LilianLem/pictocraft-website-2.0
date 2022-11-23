<?php

namespace App\Entity\External\Steam;

use App\Entity\Core\UserSteamGame;
use App\Repository\External\Steam\SteamGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// TODO : essayer de remplacer par IGDB qui offre plus de données, si l'ID Steam peut être comparé à l'ID IGDB
#[ORM\Entity(repositoryClass: SteamGameRepository::class)]
class SteamGame
{
    // ID Steam à insérer à la création
    #[ORM\Id]
    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Positive(message: "L'ID Steam du jeu doit être positif")]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    #[Assert\Length(max: 128, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Assert\Length(exactly: 40, exactMessage: "Le hash de l'image doit compter exactement {{ limit }} caractères")]
    private ?string $imgLogoUrl = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: UserSteamGame::class, orphanRemoval: true)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getImgLogoUrl(): ?string
    {
        return $this->imgLogoUrl;
    }

    public function setImgLogoUrl(?string $imgLogoUrl): self
    {
        $this->imgLogoUrl = $imgLogoUrl;

        return $this;
    }

    /**
     * @return Collection<int, UserSteamGame>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserSteamGame $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGame($this);
        }

        return $this;
    }

    public function removeUser(UserSteamGame $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGame() === $this) {
                $user->setGame(null);
            }
        }

        return $this;
    }
}
