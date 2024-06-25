<?php

namespace App\Entity\Shop\Discount;

use App\Entity\Core\User\User;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\Product;
use App\Repository\Shop\Discount\ConstraintGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConstraintGroupRepository::class)]
#[ORM\Table(name: 'shop_discount_constraint_group')]
class ConstraintGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'constraintGroups')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Discount $discount = null;

    #[ORM\OneToMany(mappedBy: 'constraintGroup', targetEntity: Constraint::class, orphanRemoval: true, cascade: ["persist", "remove"])]
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
     * @return Collection<int, Constraint>
     */
    public function getConstraints(): Collection
    {
        return $this->constraints;
    }

    public function addConstraint(Constraint $constraint): self
    {
        if (!$this->constraints->contains($constraint)) {
            $this->constraints->add($constraint);
            $constraint->setConstraintGroup($this);
        }

        return $this;
    }

    public function removeConstraint(Constraint $constraint): self
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

    /**
     * Warning: user param is only used when processing a Product object!
     */
    public function isObjectCompliant(Order|Product $object, ?User $user = null): bool
    {
        if($this->getConstraints()->isEmpty()) throw new Exception("Impossible de vérifier les contraintes de la réduction car un groupe de contraintes est vide");

        $allConstraintsNeeded = $this->getConstraintsNeeded() === 0;
        $satisfiedConstraints = 0;
        foreach($this->getConstraints() as $constraint) {
            if(
                (get_class($object) === "App\Entity\Shop\Order\Order" && $constraint->getOrderCompliance($object))
                || (get_class($object) === "App\Entity\Shop\Product" && $constraint->isProductCompliant($object, $user))
            ) $satisfiedConstraints++;
            elseif($allConstraintsNeeded) return false;

            if($satisfiedConstraints === $this->getConstraintsNeeded()) return true;
        }

        return $allConstraintsNeeded;
    }

    /**
     * @return ReadableCollection<int, OrderItem>
     */
    public function getCompliantItems(Order $order): ReadableCollection
    {
        if($this->getConstraints()->isEmpty()) throw new Exception("Impossible d'obtenir les articles respectant les contraintes de la réduction car un groupe de contraintes est vide");

        if($order->getItems()->isEmpty()) throw new Exception("Impossible d'obtenir les articles respectant les contraintes de la réduction car il n'y a aucun article dans la commande");

        /** @var int[] $compliantConstraintsPerItem */
        $compliantConstraintsPerItem = [];

        $allConstraintsNeeded = $this->getConstraintsNeeded() === 0;
        foreach($this->getConstraints() as $constraint) {
            $compliantItems = $constraint->getOrderCompliance($order, true);

            if($compliantItems->isEmpty()) {
                if($allConstraintsNeeded) return new ArrayCollection();
                continue;
            }

            foreach($compliantItems as $item) {
                $compliantConstraintsPerItem[$item->getId()] = ($compliantConstraintsPerItem[$item->getId()] ?? 0) + 1;
            }
        }

        if(!$compliantConstraintsPerItem) return new ArrayCollection();

        $constraintsNeeded = $allConstraintsNeeded ? $this->getConstraints()->count() : $this->getConstraintsNeeded();

        /** @noinspection PhpUndefinedVariableInspection */
        return $compliantItems->filter(fn(OrderItem $item) => isset($compliantConstraintsPerItem[$item->getId()]) && $compliantConstraintsPerItem[$item->getId()] === $constraintsNeeded);
    }
}
