<?php

namespace App\Entity\Shop\GameKey;

use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\RedemptionCode\RedemptionCode;
use App\Repository\Shop\GameKey\GameKeyRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameKeyRepository::class)]
#[ORM\Table(name: 'shop_game_key')]
#[UniqueEntity("code", message: "Ce code est déjà renseigné")]
class GameKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // TODO : vérifier si le paramètre unique est fonctionnel
    #[ORM\Column(unique: true, length: 29)]
    #[Assert\Length(max: 29, maxMessage: "Le code ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $gameName = null;

    #[ORM\Column(type: "game_key_type_enum")]
    #[Assert\NotBlank]
    private ?TypeEnum $keyType = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Les genres ne doivent pas dépasser {{ limit }} caractères")]
    private ?string $genres = null;

    // TODO : à transformer en relation avec SteamGame (il faut mettre en place un système qui récupère les données du jeu et l'ajoute à la table s'il n'est pas déjà en cache)
    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "L'ID Steam du jeu doit être positif")]
    private ?int $steamId = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Le nom du site ne doit pas dépasser {{ limit }} caractères")]
    private ?string $shopSource = null;

    #[ORM\ManyToOne(inversedBy: 'gameKeys')]
    private ?OrderItem $orderItem = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, minMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\DateTime]
    private ?DateTimeImmutable $addedAt = null;

    #[ORM\Column(options: ["default" => true])]
    #[Assert\NotBlank]
    private ?bool $availableToDraw = null;

    #[ORM\Column(type: "game_key_destination_enum")]
    #[Assert\NotBlank]
    private ?DestinationEnum $destination = null;

    #[ORM\OneToOne(inversedBy: 'gameKey', cascade: ['persist', 'remove'])]
    private ?RedemptionCode $redeemedCode = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Range(min: 1, max: 5, minMessage: "La rareté doit être comprise entre 1 et 5", maxMessage: "La rareté doit être comprise entre 1 et 5")]
    #[Assert\NotBlank]
    private ?int $rarity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getGameName(): ?string
    {
        return $this->gameName;
    }

    public function setGameName(string $gameName): self
    {
        $this->gameName = $gameName;

        return $this;
    }

    public function getKeyType(): ?TypeEnum
    {
        return $this->keyType;
    }

    public function setKeyType(TypeEnum $keyType): self
    {
        $this->keyType = $keyType;

        return $this;
    }

    public function getGenres(): ?string
    {
        return $this->genres;
    }

    public function setGenres(?string $genres): self
    {
        $this->genres = $genres;

        return $this;
    }

    public function getSteamId(): ?int
    {
        return $this->steamId;
    }

    public function setSteamId(?int $steamId): self
    {
        $this->steamId = $steamId;

        return $this;
    }

    public function getShopSource(): ?string
    {
        return $this->shopSource;
    }

    public function setShopSource(?string $shopSource): self
    {
        $this->shopSource = $shopSource;

        return $this;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItem $orderItem): self
    {
        $this->orderItem = $orderItem;

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

    public function getAddedAt(): ?DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function isAvailableToDraw(): ?bool
    {
        return $this->availableToDraw;
    }

    public function setAvailableToDraw(bool $availableToDraw): self
    {
        $this->availableToDraw = $availableToDraw;

        return $this;
    }

    public function getDestination(): ?DestinationEnum
    {
        return $this->destination;
    }

    public function setDestination(DestinationEnum $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getRedeemedCode(): ?RedemptionCode
    {
        return $this->redeemedCode;
    }

    public function setRedeemedCode(?RedemptionCode $redeemedCode): self
    {
        $this->redeemedCode = $redeemedCode;

        return $this;
    }

    public function getRarity(): ?int
    {
        return $this->rarity;
    }

    public function setRarity(int $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }
}
