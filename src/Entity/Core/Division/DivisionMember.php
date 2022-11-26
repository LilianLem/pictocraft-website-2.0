<?php

namespace App\Entity\Core\Division;

use App\Entity\Core\User\User;
use App\Repository\Core\Division\DivisionMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DivisionMemberRepository::class)]
#[ORM\UniqueConstraint("division_user_unique", columns: ["user_id", "division_id"])]
#[UniqueEntity(
    fields: ["user", "division"],
    errorPath: "division",
    message: "Cet utilisateur fait déjà partie de cette division",
)]
class DivisionMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Division $division = null;

    #[ORM\Column(type: "division_role_enum")]
    #[Assert\NotBlank]
    private ?RoleEnum $role = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function getRole(): ?RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): self
    {
        $this->role = $role;

        return $this;
    }
}
