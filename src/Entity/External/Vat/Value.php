<?php

namespace App\Entity\External\Vat;

use App\Repository\External\Vat\ValueRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ValueRepository::class)]
#[ORM\Table(name: 'vat_value')]
#[ORM\UniqueConstraint("vat_value_unique", columns: ["rate_id", "value", "end_at"])]
#[UniqueEntity(
    fields: ["rate", "value", "endAt"],
    errorPath: "value",
    ignoreNull: false,
    message: "Cette valeur est déjà présente sur ce taux de TVA avec cette date de validité",
)]
class Value
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vatRateValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?VatRate $rate = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\PositiveOrZero(message: "La valeur du taux de TVA ne peut pas être négative")]
    #[Assert\LessThanOrEqual(10000, message: "La valeur du taux de TVA ne peut pas dépasser 100%")]
    #[Assert\NotBlank]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Assert\Date]
    private ?DateTimeImmutable $endAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?VatRate
    {
        return $this->rate;
    }

    public function setRate(?VatRate $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getMultiplierValue(): ?float
    {
        return is_null($this->value) ? null : (1 + $this->value / 10000);
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getHTPriceFromTTC(int $priceTTC): int
    {
        return round($priceTTC / $this->getMultiplierValue());
    }
}
