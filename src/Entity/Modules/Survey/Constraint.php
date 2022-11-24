<?php

namespace App\Entity\Modules\Survey;

use App\Entity\Core\Role;
use App\Entity\Core\User\User;
use App\Repository\Modules\Survey\ConstraintRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConstraintRepository::class)]
#[ORM\Table(name: 'survey_constraint')]
class Constraint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'constraints')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Survey $survey = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Role $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

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

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
