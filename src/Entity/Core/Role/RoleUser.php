<?php

namespace App\Entity\Core\Role;

use App\Entity\Core\User\User;
use App\Repository\Core\Role\RoleUserRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoleUserRepository::class)]
#[ORM\UniqueConstraint("role_user_unique", columns: ["role_id", "user_id"])]
#[UniqueEntity(
    fields: ["role", "user"],
    errorPath: "role",
    message: "Cet utilisateur possède déjà ce rôle",
)]
#[Assert\When(
    expression: "this.getStartAt() && this.getEndAt()",
    constraints: [
        new Assert\Expression(
            "this.getEndAt() > this.getStartAt()",
            message: "La date de fin doit être ultérieure à la date de début"
        )
    ]
)]
class RoleUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Role $role = null;

    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $endAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

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

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }
}
