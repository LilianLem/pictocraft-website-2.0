<?php

namespace App\Entity\Shop\OrderItem;

use App\Repository\Shop\OrderItem\OrderItemStatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemStatusRepository::class)]
#[ORM\Table(name: 'shop_order_item_status')]
class OrderItemStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statusHistory')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?OrderItem $orderItem = null;

    #[ORM\Column(type: 'order_item_status_enum')]
    #[Assert\NotBlank]
    private $status = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
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
