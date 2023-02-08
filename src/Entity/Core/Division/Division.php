<?php

namespace App\Entity\Core\Division;

use App\Repository\Core\Division\DivisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DivisionRepository::class)]
#[ORM\UniqueConstraint("division_unique", columns: ["name", "parent_id"])]
#[ORM\UniqueConstraint("division_slug_unique", columns: ["slug", "parent_id"])]
#[UniqueEntity(
    fields: ["name", "parent"],
    errorPath: "name",
    message: "Cette division existe déjà. Si tu souhaites donner un même nom à des sous-divisions de divisions différentes, il faut d'abord créer la division principale, puis la sélectionner lors de la création de la sous-division",
)]
#[UniqueEntity(
    fields: ["slug", "parent"],
    errorPath: "slug",
    message: "Ce slug est déjà utilisé sur une division avec le même parent",
)]
class Division
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(min: 3, max: 64, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subdivisions')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $subdivisions;

    #[ORM\OneToMany(mappedBy: 'division', targetEntity: DivisionMember::class, orphanRemoval: true)]
    private Collection $members;

    // Sert à trouver les rôles correspondant à la division et à ajouter/supprimer le bon rôle à un utilisateur lorsqu'il fait partie d'une division (ex. : si le préfixe est DISCORD et qu'on ajoute un utilisateur avec le rôle de division ASSISTANT, on cherche donc un rôle nommé ROLE_DISCORD_ASSISTANT dans Core\Role\Role)
    #[ORM\Column(length: 17, unique: true, nullable: true)]
    #[Assert\Length(max: 17, maxMessage: "Le préfixe de rôle interne ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/^[A-Z]+$/', message: "Le préfixe de rôle interne doit être uniquement composé de majuscules non accentuées")]
    private ?string $internalRolePrefix = null;

    public function __construct()
    {
        $this->subdivisions = new ArrayCollection();
        $this->members = new ArrayCollection();
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
    public function getSubdivisions(): Collection
    {
        return $this->subdivisions;
    }

    /**
     * @return Collection<int, DivisionMember>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(DivisionMember $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setDivision($this);
        }

        return $this;
    }

    public function removeMember(DivisionMember $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getDivision() === $this) {
                $member->setDivision(null);
            }
        }

        return $this;
    }

    public function getInternalRolePrefix(): ?string
    {
        return $this->internalRolePrefix;
    }

    public function setInternalRolePrefix(?string $internalRolePrefix): self
    {
        $this->internalRolePrefix = $internalRolePrefix;

        return $this;
    }
}
