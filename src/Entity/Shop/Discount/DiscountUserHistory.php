<?php

namespace App\Entity\Shop\Discount;

use App\Entity\Core\User\User;
use App\Repository\Shop\Discount\DiscountUserHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscountUserHistoryRepository::class)]
#[ORM\Table(name: 'shop_discount_user_history')]
#[ORM\UniqueConstraint("discount_user_history_unique", columns: ["discount_id", "user_id"])]
#[UniqueEntity(
    fields: ["discount", "user"],
    errorPath: "discount",
    ignoreNull: true,
    message: "L'historique de cette réduction existe déjà pour cet utilisateur",
)]
class DiscountUserHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usersHistory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Discount $discount = null;

    #[ORM\ManyToOne(inversedBy: 'discountsHistory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Only count uses on valid orders (not cancelled/aborted/expired, except when order has been paid and then refunded via return or withdrawal)
    #[ORM\Column]
    #[Assert\Positive(message: "Le nombre d'utilisations doit être supérieur à 0")]
    #[Assert\NotBlank]
    private ?int $numberOfUses = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNumberOfUses(): ?int
    {
        return $this->numberOfUses;
    }

    public function setNumberOfUses(int $numberOfUses): self
    {
        if($numberOfUses < 1) {
            throw new Exception("Le nombre d'utilisations est incorrect : il doit être supérieur à 0");
        }

        $this->numberOfUses = $numberOfUses;

        return $this;
    }

    public function incrementNumberOfUses(): self
    {
        $this->numberOfUses++;

        return $this;
    }

    public function decrementNumberOfUses(): self
    {
        if($this->numberOfUses < 2) {
            throw new Exception("Impossible de décrémenter le nombre d'utilisations : celui-ci doit être supérieur à 0. S'il s'agit du comportement voulu, il est nécessaire de supprimer l'historique de cette réduction pour l'utilisateur.");
        }

        $this->numberOfUses--;

        return $this;
    }
}
