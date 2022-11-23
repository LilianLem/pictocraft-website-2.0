<?php

namespace App\Entity\Core;

use App\Repository\Core\DivisionMemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DivisionMemberRepository::class)]
class DivisionMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Division $division = null;

    #[ORM\Column(type: "division_role_enum")]
    private ?DivisionRoleEnum $role = null;

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

    public function getRole(): ?DivisionRoleEnum
    {
        return $this->role;
    }

    public function setRole(DivisionRoleEnum $role): self
    {
        $this->role = $role;

        return $this;
    }
}
