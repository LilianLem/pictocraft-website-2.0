<?php

namespace App\Entity\Shop\Order;

use App\Entity\Core\User\User;
use App\Entity\External\Geo\Country;
use App\Entity\External\Geo\France\CommunePostalData;
use App\Entity\External\Vat\Value;
use App\Entity\External\Vat\VatRate;
use App\Entity\Shop\Discount\AppliedDiscount;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\Payment\Payment;
use App\Repository\Shop\Order\OrderRepository;
use DateTime;
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
    #[Assert\PositiveOrZero(message: "Le sous-total TTC des articles ne peut pas être négatif")]
    private ?int $baseSubtotalTtc = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le montant total HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $totalAmountHt = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le montant total TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $totalAmountTtc = null;

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

    /** @var Collection<int, OrderItem>  */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $items;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: AppliedDiscount::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $appliedDiscounts;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Status::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    #[ORM\OrderBy(["date" => "ASC"])]
    private Collection $statusHistory;

    // ------ Sauvegarde au moment de la commande de l'adresse définie par l'utilisateur dans ses paramètres comme étant celle à utiliser pour la facturation et pour la livraison des produits physiques le cas échéant ------ \\
    // ------ Ces champs sont obligatoires, sauf pour les activations de code (qui créent également une commande) dans le cas où l'adresse n'est pas renseignée par l'utilisateur dans ses paramètres ------ \\
    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineBuildingInside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineBuildingOutside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineStreet = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineHamlet = null;

    #[ORM\ManyToOne]
    private ?CommunePostalData $addressCommunePostalData = null;

    // Pays d'habitation actuel
    #[ORM\ManyToOne]
    private ?Country $addressCountry = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $payments;

    // ------ Sauvegarde au moment de la commande de l'adresse définie par l'utilisateur dans ses paramètres comme étant celle à utiliser pour la facturation et pour la livraison des produits physiques le cas échéant ------ \\

    public function __construct()
    {
        $this->baseSubtotalTtc = 0;
        $this->totalAmountTtc = 0;
        $this->items = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function getBaseSubtotalHt(): ?int
    {
        return $this->getItems()->isEmpty()
            ? 0
            : $this->getItems()->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + ($item->getBasePriceHtPerUnit() * $item->getQuantity()), initial: 0)
        ;
    }

    public function getBaseSubtotalTtc(): ?int
    {
        return $this->baseSubtotalTtc;
    }

//    public function setBaseSubtotalTtc(int $baseSubtotalTtc): self
//    {
//        $this->baseSubtotalTtc = $baseSubtotalTtc;
//
//        return $this;
//    }

    public function updateBaseSubtotalTtc(): self
    {
        $this->baseSubtotalTtc = $this->getItems()->isEmpty()
            ? 0
            : $this->getItems()->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + ($item->getBasePriceTtcPerUnit() * $item->getQuantity()), initial: 0)
        ;

        return $this;
    }

    public function getDiscountsSubtotal(): ?int
    {
        if($this->getItems()->isEmpty()) {
            return 0;
        }

        $discountsSubtotal = $this->getItems()->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + $item->getDiscountsTotal(), initial: 0);

        if(!$this->getAppliedDiscounts()->isEmpty()) {
            $discountsSubtotal += $this->getAppliedDiscounts()->reduce(fn(int $accumulator, AppliedDiscount $ad): int => $accumulator + $ad->getAmount(), initial: 0);
        }

        return $discountsSubtotal;
    }

    public function getDiscountsOnOrderSubtotal(): int
    {
        return $this->getAppliedDiscounts()->isEmpty()
            ? 0
            : $this->getAppliedDiscounts()->reduce(fn(int $accumulator, AppliedDiscount $ad): int => $accumulator + $ad->getAmount(), initial: 0)
        ;
    }

    public function getTotalAmountHtBeforeOrderDiscounts(?VatRate $vatRate = null): int
    {
        $items = $this->getItems();
        if($items->isEmpty()) return 0;

        if($vatRate) {
            $items = $items->filter(fn(OrderItem $item) => $item->getProduct()->getApplicableVatRate() === $vatRate);
            if($items->isEmpty()) return 0;
        }

        return $items->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + $item->getTotalAmountHt(), initial: 0);
    }

    public function getTotalAmountHt(): ?int
    {
        return $this->totalAmountHt;
    }

    public function updateTotalAmountHt(): self
    {
        if($this->getItems()->isEmpty()) {
            $this->totalAmountHt = 0;
            return $this;
        }

        if($this->getAppliedDiscounts()->isEmpty()) {
            $this->totalAmountHt = $this->getItems()->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + $item->getTotalAmountHt(), initial: 0);
            return $this;
        }

        $this->totalAmountHt = array_reduce($this->getTotalAmountsPerVatRate(), fn($sum, $vatRateTotals) => $sum + $vatRateTotals["ht"]);

        // Not supposed to happen
        if($this->totalAmountHt < 0) $this->totalAmountHt = 0;

        return $this;
    }

    public function getTotalAmountTtcBeforeOrderDiscounts(?VatRate $vatRate = null): int
    {
        $items = $this->getItems();
        if($items->isEmpty()) return 0;

        if($vatRate) {
            $items = $items->filter(fn(OrderItem $item) => $item->getProduct()->getApplicableVatRate() === $vatRate);
            if($items->isEmpty()) return 0;
        }

        return $items->reduce(fn(int $accumulator, OrderItem $item): int => $accumulator + $item->getTotalAmountTtc(), initial: 0);
    }

    public function getTotalAmountTtc(): ?int
    {
        return $this->totalAmountTtc;
    }

//    public function setTotalAmountTtc(int $totalAmountTtc): self
//    {
//        $this->totalAmountTtc = $totalAmountTtc;
//
//        return $this;
//    }

    public function updateTotalAmountTtc(): self
    {
        $this->totalAmountTtc = $this->getBaseSubtotalTtc() - $this->getDiscountsSubtotal();

        // Not supposed to happen
        if($this->totalAmountTtc < 0) $this->totalAmountTtc = 0;

        return $this;
    }

    /** @return array{
     *     array{
     *       vatRateValue: Value,
     *       ttc: int,
     *       ht: int
     *     }
     *  }
     */
    public function getTotalAmountsPerVatRate(): array
    {
        $orderTotal = $this->getTotalAmountTtcBeforeOrderDiscounts();

        if($this->getAppliedDiscounts()->isEmpty()) {
            $calculateWithOrderDiscounts = false;
            $orderDiscounts = $this->getDiscountsOnOrderSubtotal();
        } else {
            $calculateWithOrderDiscounts = true;
        }

        $vatRates = $this->getItemsVatRates();
        $totalsPerVatRate = [];

        foreach($vatRates as $vatRate) {
            $amount = $this->getTotalAmountTtcBeforeOrderDiscounts($vatRate);
            if($calculateWithOrderDiscounts) $ttcTotalForVatRate = round($amount - $orderDiscounts * ($amount / $orderTotal));
            $vatRateValue = $vatRate->getValueAtDate($this->getCreatedAt() ?? new DateTime());

            $totalsPerVatRate[] = array(
                "vatRateValue" => $vatRateValue,
                "ttc" => $calculateWithOrderDiscounts ? $ttcTotalForVatRate : $amount,
                "ht" => $calculateWithOrderDiscounts ? $vatRateValue->getHtPriceFromTtc($ttcTotalForVatRate) : $this->getTotalAmountHtBeforeOrderDiscounts($vatRate)
            );
        }

        return $totalsPerVatRate;
    }

    public function updateTotals(): self
    {
        return $this->updateBaseSubtotalTtc()->updateTotalAmountTtc()->updateTotalAmountHt();
    }

    /**
     * @return Collection<int, VatRate>
     */
    public function getItemsVatRates(): Collection
    {
        $items = $this->getItems();
        if($items->isEmpty()) return new ArrayCollection();

        /** @var VatRate[] $vatRates */
        $vatRates = [];
        foreach($items as $item) {
            $vatRate = $item->getProduct()->getApplicableVatRate();
            if(!isset($vatRates[$vatRate->getId()])) {
                $vatRates[$vatRate->getId()] = $vatRate;
            }
        }

        return new ArrayCollection($vatRates);
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

        $this->updateTotals();

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

        $this->updateTotals();

        return $this;
    }

    public function getAddressLineBuildingInside(): ?string
    {
        return $this->addressLineBuildingInside;
    }

    public function setAddressLineBuildingInside(?string $addressLineBuildingInside): self
    {
        $this->addressLineBuildingInside = $addressLineBuildingInside;

        return $this;
    }

    public function getAddressLineBuildingOutside(): ?string
    {
        return $this->addressLineBuildingOutside;
    }

    public function setAddressLineBuildingOutside(?string $addressLineBuildingOutside): self
    {
        $this->addressLineBuildingOutside = $addressLineBuildingOutside;

        return $this;
    }

    public function getAddressLineStreet(): ?string
    {
        return $this->addressLineStreet;
    }

    public function setAddressLineStreet(?string $addressLineStreet): self
    {
        $this->addressLineStreet = $addressLineStreet;

        return $this;
    }

    public function getAddressLineHamlet(): ?string
    {
        return $this->addressLineHamlet;
    }

    public function setAddressLineHamlet(?string $addressLineHamlet): self
    {
        $this->addressLineHamlet = $addressLineHamlet;

        return $this;
    }

    public function getAddressCommunePostalData(): ?CommunePostalData
    {
        return $this->addressCommunePostalData;
    }

    public function setAddressCommunePostalData(?CommunePostalData $addressCommunePostalData): self
    {
        $this->addressCommunePostalData = $addressCommunePostalData;

        return $this;
    }

    public function getAddressCountry(): ?Country
    {
        return $this->addressCountry;
    }

    public function setAddressCountry(?Country $addressCountry): self
    {
        $this->addressCountry = $addressCountry;

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

    public function getCurrentStatus(): ?Status
    {
        $statusHistory = $this->getStatusHistory();
        return $statusHistory->isEmpty()
            ? null
            : $statusHistory->last()
        ;
    }

    public function getStatusDetails(StatusEnum $status): ?Status
    {
        $statusHistory = $this->getStatusHistory();
        return $statusHistory->isEmpty()
            ? null
            : $statusHistory->findFirst(fn(int $key, Status $statusToCompare) => $statusToCompare->getStatus() === $status)
        ;
    }


    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setOrder($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getOrder() === $this) {
                $payment->setOrder(null);
            }
        }

        return $this;
    }
}
