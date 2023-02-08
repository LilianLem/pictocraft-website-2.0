<?php

namespace App\Entity\Core\Role;

use App\Repository\Core\Role\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// TODO : vérifier si la classe est fonctionnelle en remplacement du rôle d'origine de Symfony
#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity("name", message: "Ce rôle existe déjà")]
#[UniqueEntity("slug", message: "Ce slug est déjà utilisé")]
#[UniqueEntity("internalName", message: "Ce nom interne est déjà utilisé")]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\Length(max: 32, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 32, unique: true, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères")]
    private ?string $slug = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\Length(min: 6, max: 32, minMessage: "Le nom interne doit comporter au moins {{ limit }} caractères", maxMessage: "Le nom interne ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/^ROLE(_[A-Z]+)+$/', message: "Le nom interne doit respecter le format ROLE_XXXX (exemple : ROLE_MY_RANK)")]
    #[Assert\NotBlank]
    private ?string $internalName = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $visible = null;

    #[ORM\Column(length: 6, nullable: true)]
    #[Assert\Length(max: 6, maxMessage: "Le nom de la couleur ne doit pas dépasser {{ limit }} caractères")]
    private ?string $color = null;

    #[ORM\Column(length: 32, unique: true, nullable: true)]
    #[Assert\Length(min: 17, max: 32, minMessage: "L'ID Discord doit comporter au moins {{ limit }} caractères", maxMessage: "L'ID Discord ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/\d+/', message: "L'ID Discord doit être numérique")]
    private ?string $discordId = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childrenRoles')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $childrenRoles;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: RoleUser::class, orphanRemoval: true)]
    private Collection $users;

    public function __construct()
    {
        $this->visible = false;
        $this->childrenRoles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    // Nécessaire pour être fonctionnel avec le système de rôles de Symfony (voir \Symfony\Component\Security\Core\Role\Role)
    public function __toString(): string
    {
        return $this->getInternalName();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getInternalName(): ?string
    {
        return $this->internalName;
    }

    public function setInternalName(string $internalName): self
    {
        $this->internalName = $internalName;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildrenRoles(): Collection
    {
        return $this->childrenRoles;
    }

    public function addChildrenRole(self $childrenRole): self
    {
        if (!$this->childrenRoles->contains($childrenRole)) {
            $this->childrenRoles->add($childrenRole);
            $childrenRole->setParent($this);
        }

        return $this;
    }

    public function removeChildrenRole(self $childrenRole): self
    {
        if ($this->childrenRoles->removeElement($childrenRole)) {
            // set the owning side to null (unless already changed)
            if ($childrenRole->getParent() === $this) {
                $childrenRole->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoleUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(RoleUser $roleUser): self
    {
        if (!$this->users->contains($roleUser)) {
            $this->users->add($roleUser);
            $roleUser->setRole($this);
        }

        return $this;
    }

    public function removeUser(RoleUser $roleUser): self
    {
        if ($this->users->removeElement($roleUser)) {
            // set the owning side to null (unless already changed)
            if ($roleUser->getRole() === $this) {
                $roleUser->setRole(null);
            }
        }

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
}
