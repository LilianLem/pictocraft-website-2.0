<?php

namespace App\Entity\Shop\RedemptionCode;

use App\Entity\Core\User\User;
use App\Entity\Shop\GameKey\GameKey;
use App\Entity\Shop\GameKey\TypeEnum;
use App\Repository\Shop\RedemptionCode\RedemptionCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RedemptionCodeRepository::class)]
#[ORM\Table(name: 'shop_redemption_code')]
#[UniqueEntity("code", message: "Ce code est déjà utilisé")]
class RedemptionCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Assert\Length(exactly: 10, exactMessage: "Le code doit compter exactement {{ limit }} caractères")]
    #[Assert\Regex(pattern: '\d{10}', message: "Le code doit être un nombre de 10 chiffres")]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'redemptionCodes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'redemptionCodes')]
    private ?RedemptionCodeAccess $access = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    #[ORM\Column(type: "game_key_type_enum")]
    #[Assert\NotBlank]
    private ?TypeEnum $keyType = null;

    #[ORM\OneToOne(mappedBy: 'redeemedCode', cascade: ['persist', 'remove'])]
    private ?GameKey $gameKey = null;

    #[ORM\Column(options: ["default" => true])]
    #[Assert\NotBlank]
    private ?bool $available = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccess(): ?RedemptionCodeAccess
    {
        return $this->access;
    }

    public function setAccess(?RedemptionCodeAccess $access): self
    {
        $this->access = $access;

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

    public function getKeyType(): ?TypeEnum
    {
        return $this->keyType;
    }

    public function setKeyType(TypeEnum $keyType): self
    {
        $this->keyType = $keyType;

        return $this;
    }

    public function getGameKey(): ?GameKey
    {
        return $this->gameKey;
    }

    public function setGameKey(?GameKey $gameKey): self
    {
        // unset the owning side of the relation if necessary
        if ($gameKey === null && $this->gameKey !== null) {
            $this->gameKey->setRedeemedCode(null);
        }

        // set the owning side of the relation if necessary
        if ($gameKey !== null && $gameKey->getRedeemedCode() !== $this) {
            $gameKey->setRedeemedCode($this);
        }

        $this->gameKey = $gameKey;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }
}
