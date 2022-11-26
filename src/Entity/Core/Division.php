<?php

namespace App\Entity\Core;

use App\Repository\Core\DivisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DivisionRepository::class)]
#[ORM\UniqueConstraint("division_unique", columns: ["name", "parent_id"])]
#[UniqueEntity(
    fields: ["name"],
    errorPath: "name",
    message: "Cette division existe déjà. Si tu souhaites donner un même nom à des sous-divisions de divisions différentes, il faut d'abord créer la division principale, puis la sélectionner lors de la création de la sous-division",
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

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subdivisions')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $subdivisions;

    #[ORM\OneToMany(mappedBy: 'division', targetEntity: DivisionMember::class, orphanRemoval: true)]
    private Collection $members;

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
}
