<?php

namespace App\Entity\Shop\OrderItem;

use App\Entity\Core\User\User;
use App\Entity\Shop\Delivery\Delivery;
use App\Entity\Shop\Discount\AppliedDiscount;
use App\Entity\Shop\GameKey\GameKey;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Product;
use App\Repository\Shop\OrderItem\OrderItemRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ORM\Table(name: 'shop_order_item')]
#[ORM\UniqueConstraint("order_item_unique", columns: ["order_id", "item_id"])]
#[UniqueEntity(
    fields: ["order", "item"],
    errorPath: "item",
    message: "Cet article est déjà présent dans la commande",
)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Product $item = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix de base HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $basePriceHT = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix de base TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $basePriceTTC = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceHT = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceTTC = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Delivery $delivery = null;

    // TODO : vérifier si la mise à jour de la date est fonctionnelle, càd seulement quand des choses sont modifiées
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"], columnDefinition: "DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL on update CURRENT_TIMESTAMP")]
    #[Assert\DateTime]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(options: ["default" => 1, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "La quantité ne peut pas être négative")]
    #[Assert\NotBlank]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'giftedItems')]
    private ?User $giftedTo = null;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: GameKey::class)]
    private Collection $gameKeys;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: AppliedDiscount::class)]
    private Collection $appliedDiscounts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryTrackingLink = null;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: Status::class, orphanRemoval: true)]
    private Collection $statusHistory;

    // Indique l'article qui a servi à renouveler cet article
    #[ORM\OneToOne(inversedBy: 'precededBy', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $followedBy = null;

    // Indique l'article d'origine qui a été renouvelé par cet article
    #[ORM\OneToOne(mappedBy: 'followedBy', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $precededBy = null;

    public function __construct()
    {
        $this->gameKeys = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getItem(): ?Product
    {
        return $this->item;
    }

    public function setItem(?Product $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getBasePriceHT(): ?int
    {
        return $this->basePriceHT;
    }

    public function setBasePriceHT(int $basePriceHT): self
    {
        $this->basePriceHT = $basePriceHT;

        return $this;
    }

    public function getBasePriceTTC(): ?int
    {
        return $this->basePriceTTC;
    }

    public function setBasePriceTTC(int $basePriceTTC): self
    {
        $this->basePriceTTC = $basePriceTTC;

        return $this;
    }

    public function getPriceHT(): ?int
    {
        return $this->priceHT;
    }

    public function setPriceHT(int $priceHT): self
    {
        $this->priceHT = $priceHT;

        return $this;
    }

    public function getPriceTTC(): ?int
    {
        return $this->priceTTC;
    }

    public function setPriceTTC(int $priceTTC): self
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getGiftedTo(): ?User
    {
        return $this->giftedTo;
    }

    public function setGiftedTo(?User $giftedTo): self
    {
        $this->giftedTo = $giftedTo;

        return $this;
    }

    /**
     * @return Collection<int, GameKey>
     */
    public function getGameKeys(): Collection
    {
        return $this->gameKeys;
    }

    public function addGameKey(GameKey $gameKey): self
    {
        if (!$this->gameKeys->contains($gameKey)) {
            $this->gameKeys->add($gameKey);
            $gameKey->setOrderItem($this);
        }

        return $this;
    }

    public function removeGameKey(GameKey $gameKey): self
    {
        if ($this->gameKeys->removeElement($gameKey)) {
            // set the owning side to null (unless already changed)
            if ($gameKey->getOrderItem() === $this) {
                $gameKey->setOrderItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AppliedDiscount>
     */
    public function getAppliedDiscounts(): Collection
    {
        return $this->appliedDiscounts;
    }

    public function addAppliedDiscount(AppliedDiscount $appliedDiscount): self
    {
        if (!$this->appliedDiscounts->contains($appliedDiscount)) {
            $this->appliedDiscounts->add($appliedDiscount);
            $appliedDiscount->setOrderItem($this);
        }

        return $this;
    }

    public function removeAppliedDiscount(AppliedDiscount $appliedDiscount): self
    {
        if ($this->appliedDiscounts->removeElement($appliedDiscount)) {
            // set the owning side to null (unless already changed)
            if ($appliedDiscount->getOrderItem() === $this) {
                $appliedDiscount->setOrderItem(null);
            }
        }

        return $this;
    }

    public function getDeliveryTrackingLink(): ?string
    {
        return $this->deliveryTrackingLink;
    }

    public function setDeliveryTrackingLink(?string $deliveryTrackingLink): self
    {
        $this->deliveryTrackingLink = $deliveryTrackingLink;

        return $this;
    }

    /**
     * @return Collection<int, Status>
     */
    public function getStatusHistory(): Collection
    {
        return $this->statusHistory;
    }

    // TODO : méthode à retravailler pour juste donner le statut à ajouter
    public function addStatusToHistory(Status $statusHistory): self
    {
        if (!$this->statusHistory->contains($statusHistory)) {
            $this->statusHistory->add($statusHistory);
            $statusHistory->setOrderItem($this);
        }

        return $this;
    }

    public function removeStatusFromHistory(Status $statusHistory): self
    {
        if ($this->statusHistory->removeElement($statusHistory)) {
            // set the owning side to null (unless already changed)
            if ($statusHistory->getOrderItem() === $this) {
                $statusHistory->setOrderItem(null);
            }
        }

        return $this;
    }

    public function getFollowedBy(): ?self
    {
        return $this->followedBy;
    }

    public function setFollowedBy(?self $followedBy): self
    {
        $this->followedBy = $followedBy;

        return $this;
    }

    public function getPrecededBy(): ?self
    {
        return $this->precededBy;
    }

    public function setPrecededBy(?self $precededBy): self
    {
        // unset the owning side of the relation if necessary
        if ($precededBy === null && $this->precededBy !== null) {
            $this->precededBy->setFollowedBy(null);
        }

        // set the owning side of the relation if necessary
        if ($precededBy !== null && $precededBy->getFollowedBy() !== $this) {
            $precededBy->setFollowedBy($this);
        }

        $this->precededBy = $precededBy;

        return $this;
    }
}
