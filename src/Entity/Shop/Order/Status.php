<?php

namespace App\Entity\Shop\Order;

use App\Repository\Shop\Order\StatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\Table(name: 'shop_order_status')]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statusHistory')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Order $order = null;

    #[ORM\Column(type: 'order_status_enum')]
    #[Assert\NotBlank]
    private $status = null;

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

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): self
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