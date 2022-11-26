<?php

namespace App\Entity\Shop\Discount;

use App\Repository\Shop\Discount\DiscountConstraintGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscountConstraintGroupRepository::class)]
#[ORM\Table(name: 'shop_discount_constraint_group')]
class DiscountConstraintGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'constraintGroups')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Discount $discount = null;

    #[ORM\OneToMany(mappedBy: 'constraintGroup', targetEntity: DiscountConstraint::class, orphanRemoval: true)]
    private Collection $constraints;

    // 0 = toutes les contraintes nécessaires
    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero(message: "Au moins 1 contrainte est nécessaire pour que le groupe fonctionne. Si toutes les contraintes du groupe sont nécessaires, indique 0")]
    private ?int $constraintsNeeded = null;

    public function __construct()
    {
        $this->constraints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return Collection<int, DiscountConstraint>
     */
    public function getConstraints(): Collection
    {
        return $this->constraints;
    }

    public function addConstraint(DiscountConstraint $constraint): self
    {
        if (!$this->constraints->contains($constraint)) {
            $this->constraints->add($constraint);
            $constraint->setConstraintGroup($this);
        }

        return $this;
    }

    public function removeConstraint(DiscountConstraint $constraint): self
    {
        if ($this->constraints->removeElement($constraint)) {
            // set the owning side to null (unless already changed)
            if ($constraint->getConstraintGroup() === $this) {
                $constraint->setConstraintGroup(null);
            }
        }

        return $this;
    }

    public function getConstraintsNeeded(): ?int
    {
        return $this->constraintsNeeded;
    }

    public function setConstraintsNeeded(int $constraintsNeeded): self
    {
        $this->constraintsNeeded = $constraintsNeeded;

        return $this;
    }
}
