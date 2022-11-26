<?php

namespace App\Entity\Core\Badge;

use App\Repository\Core\Badge\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
#[UniqueEntity("name", message: "Ce badge existe déjà")]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(min: 5, max: 64, minMessage: "Le nom de l'image doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom de l'image ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'badges')]
    private ?BadgeCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'badge', targetEntity: BadgeUser::class, orphanRemoval: true)]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?BadgeCategory
    {
        return $this->category;
    }

    public function setCategory(?BadgeCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, BadgeUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(BadgeUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setBadge($this);
        }

        return $this;
    }

    public function removeUser(BadgeUser $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBadge() === $this) {
                $user->setBadge(null);
            }
        }

        return $this;
    }
}
