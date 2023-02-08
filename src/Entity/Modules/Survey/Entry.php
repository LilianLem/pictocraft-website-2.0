<?php

namespace App\Entity\Modules\Survey;

use App\Entity\Core\User\User;
use App\Repository\Modules\Survey\EntryRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
#[ORM\Table(name: 'survey_entry')]
class Entry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Survey $survey = null;

    // Obligatoire si la propriété anonymous de Survey est sur false
    #[ORM\ManyToOne(inversedBy: 'surveyEntries')]
    private ?User $user = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(options: ["default" => 0])]
    #[Assert\PositiveOrZero(message: "Le nombre de modifications effectuées doit être positif ou nul")]
    #[Assert\NotBlank]
    private ?int $modifications = null;

    // TODO : vérifier si la mise à jour de la date est fonctionnelle, càd seulement quand des choses sont modifiées
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"], columnDefinition: "DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL on update CURRENT_TIMESTAMP")]
    private ?DateTimeInterface $modifiedAt = null;

    #[ORM\OneToMany(mappedBy: 'entry', targetEntity: Answer::class, orphanRemoval: true)]
    #[Assert\NotBlank]
    private Collection $answers;

    public function __construct()
    {
        $this->modifications = 0;
        $this->answers = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifications(): ?int
    {
        return $this->modifications;
    }

    public function setModifications(int $modifications): self
    {
        $this->modifications = $modifications;

        return $this;
    }

    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setEntry($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getEntry() === $this) {
                $answer->setEntry(null);
            }
        }

        return $this;
    }
}
