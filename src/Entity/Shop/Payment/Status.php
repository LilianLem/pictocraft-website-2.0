<?php

namespace App\Entity\Shop\Payment;

use App\Repository\Shop\Payment\StatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\Table(name: 'shop_payment_status')]
#[ORM\UniqueConstraint("payment_status_unique", columns: ["payment_id", "status"])]
#[UniqueEntity(
    fields: ["payment", "status"],
    errorPath: "status",
    message: "Ce statut est déjà présent sur ce paiement",
)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statusHistory')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Payment $payment = null;

    #[ORM\Column(type: 'payment_status_enum')]
    #[Assert\NotBlank]
    private ?StatusEnum $status = null;

    #[ORM\Column]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

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
}
