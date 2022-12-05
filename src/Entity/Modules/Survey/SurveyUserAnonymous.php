<?php

namespace App\Entity\Modules\Survey;

use App\Entity\Core\User\User;
use App\Repository\Modules\Survey\SurveyUserAnonymousRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SurveyUserAnonymousRepository::class)]
#[ORM\Table(name: 'survey_user_anonymous')]
#[ORM\UniqueConstraint("survey_user_anonymous_unique", columns: ["survey_id", "user_id"])]
#[UniqueEntity(
    fields: ["survey", "user"],
    errorPath: "user",
    message: "Cet utilisateur a déjà répondu au sondage",
)]
class SurveyUserAnonymous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usersAnsweredAnonymously')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Survey $survey = null;

    #[ORM\ManyToOne(inversedBy: 'surveysAnsweredAnonymously')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Positive(message: "Le nombre de réponses doit être supérieur à 0")]
    #[Assert\NotBlank]
    private ?int $timesAnswered = null;

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

    public function getTimesAnswered(): ?int
    {
        return $this->timesAnswered;
    }

    public function setTimesAnswered(int $timesAnswered): self
    {
        $this->timesAnswered = $timesAnswered;

        return $this;
    }
}
