<?php

namespace App\Entity\Shop\Order;

use App\Entity\Core\User\User;
use App\Entity\Shop\Discount\AppliedDiscount;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\PaymentMethod\PaymentMethod;
use App\Entity\Shop\WalletTransaction;
use App\Repository\Shop\Order\OrderRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'shop_order')]
#[UniqueEntity("reference", message: "Cette référence est déjà utilisée")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // TODO : il faudra vérifier si la regex ne bloque pas l'édition des anciennes commandes avec des références en lettres, et aussi trouver comment afficher un champ dans un formulaire sans qu'il soit modifiable (champ en disabled et non prise en compte de la valeur par Symfony si modifiée autrement lors de l'enregistrement)
    #[ORM\Column(length: 9, unique: true)]
    #[Assert\Length(exactly: 9, exactMessage: "La référence doit compter exactement {{ limit }} caractères")]
    #[Assert\Regex(pattern: '\d{9}', message: "La référence doit être un nombre de 9 chiffres pour les nouvelles commandes")]
    #[Assert\NotBlank]
    private ?string $reference = null;

    // TODO : voir si possible de le mettre en not nullable avec contrainte Assert\NotBlank une fois les anciens utilisateurs ajoutés, avec les règles de confidentialité nécessaires
    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceHT = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceTTC = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\DateTime]
    private ?DateTimeImmutable $createdAt = null;

    // TODO : vérifier si la mise à jour de la date est fonctionnelle, càd seulement quand des choses sont modifiées
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"], columnDefinition: "DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL on update CURRENT_TIMESTAMP")]
    #[Assert\DateTime]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $paypalToken = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, orphanRemoval: true)]
    private Collection $items;

    #[ORM\OneToOne(mappedBy: 'order', cascade: ['persist', 'remove'])]
    private ?WalletTransaction $walletTransaction = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: AppliedDiscount::class)]
    private Collection $appliedDiscounts;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Status::class, orphanRemoval: true)]
    private Collection $statusHistory;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getPaypalToken(): ?string
    {
        return $this->paypalToken;
    }

    public function setPaypalToken(?string $paypalToken): self
    {
        $this->paypalToken = $paypalToken;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }

    public function getWalletTransaction(): ?WalletTransaction
    {
        return $this->walletTransaction;
    }

    public function setWalletTransaction(?WalletTransaction $walletTransaction): self
    {
        // unset the owning side of the relation if necessary
        if ($walletTransaction === null && $this->walletTransaction !== null) {
            $this->walletTransaction->setOrder(null);
        }

        // set the owning side of the relation if necessary
        if ($walletTransaction !== null && $walletTransaction->getOrder() !== $this) {
            $walletTransaction->setOrder($this);
        }

        $this->walletTransaction = $walletTransaction;

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
            $appliedDiscount->setOrder($this);
        }

        return $this;
    }

    public function removeAppliedDiscount(AppliedDiscount $appliedDiscount): self
    {
        if ($this->appliedDiscounts->removeElement($appliedDiscount)) {
            // set the owning side to null (unless already changed)
            if ($appliedDiscount->getOrder() === $this) {
                $appliedDiscount->setOrder(null);
            }
        }

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
            $statusHistory->setOrder($this);
        }

        return $this;
    }

    public function removeStatusFromHistory(Status $statusHistory): self
    {
        if ($this->statusHistory->removeElement($statusHistory)) {
            // set the owning side to null (unless already changed)
            if ($statusHistory->getOrder() === $this) {
                $statusHistory->setOrder(null);
            }
        }

        return $this;
    }
}
