<?php

namespace App\Entity\Core\Role;

use App\Repository\Core\Role\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity("internalName", message: "Ce nom interne est déjà utilisé")]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // Si aucun nom n'est choisi, la valeur d'internalName sera affichée
    #[ORM\Column(length: 32)]
    #[Assert\Length(max: 32, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\Length(min: 6, max: 32, minMessage: "Le nom interne doit comporter au moins {{ limit }} caractères", maxMessage: "Le nom interne ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/^ROLE(_[A-Z]+)+$/', message: "Le nom interne doit respecter le format ROLE_XXXX (exemple : ROLE_MY_RANK)")]
    #[Assert\NotBlank]
    private ?string $internalName = null;

    // Indique si le rôle est visible dans l'administration
    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $visible = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $displayedOnProfile = null;

    // Rang d'affichage sur le profil (exemple : la page d'utilisateur 1 affiche "Modérateur / VIP" et celle de l'utilisateur 2 affiche "Membre". "Modérateur" est au rang 1 et les autres sont au rang 2, car "Modérateur" s'affiche devant et ne les remplace pas)
    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 3, minMessage: "Le rang ne peut pas être inférieur à 1", maxMessage: "Le rang ne peut pas être supérieur à 3")]
    private ?int $profileDisplayRow = null;

    // Priorité d'affichage sur le profil pour les rôles de même rang (exemple : le rôle affiché d'un simple membre VIP est uniquement "VIP", car cela prend le dessus sur le rôle "Membre". "VIP" doit donc avoir une priorité supérieure à "Membre")
    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: -1, max: 10, minMessage: "La priorité ne peut pas être inférieure à -1", maxMessage: "La priorité ne peut pas être supérieure à 10")]
    private ?int $profileDisplayPriority = null;

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

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: RoleUser::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $users;

    private static string $slugProperty = "internalName";

    public function __construct()
    {
        $this->visible = false;
        $this->displayedOnProfile = false;
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

    public function isDisplayedOnProfile(): ?bool
    {
        return $this->displayedOnProfile;
    }

    public function setDisplayedOnProfile(bool $displayedOnProfile): self
    {
        $this->displayedOnProfile = $displayedOnProfile;

        return $this;
    }

    public function getProfileDisplayRow(): ?int
    {
        return $this->profileDisplayRow;
    }

    public function setProfileDisplayRow(?int $profileDisplayRow): self
    {
        $this->profileDisplayRow = $profileDisplayRow;

        return $this;
    }

    public function getProfileDisplayPriority(): ?int
    {
        return $this->profileDisplayPriority;
    }

    public function setProfileDisplayPriority(?int $profileDisplayPriority): self
    {
        $this->profileDisplayPriority = $profileDisplayPriority;

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

    public static function getSlugProperty(): string
    {
        return Role::$slugProperty;
    }
}
