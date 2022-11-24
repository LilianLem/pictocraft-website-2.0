<?php

namespace App\Entity\Modules\Survey;

use App\Entity\Core\User\User;
use App\Repository\Modules\Survey\SurveyRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
#[ORM\Table(name: 'survey_survey')]
class Survey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeInterface $endAt = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $anonymous = null;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $editable = null;

    #[ORM\Column(nullable: true)]
    private ?int $allowedModifications = null;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: Entry::class, orphanRemoval: true)]
    private Collection $entries;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: Constraint::class, orphanRemoval: true)]
    private Collection $constraints;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'surveysAnsweredAnonymously')]
    #[ORM\JoinTable(name: "survey_survey_user")]
    private Collection $usersHavingAnsweredAnonymously;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->entries = new ArrayCollection();
        $this->userConstraints = new ArrayCollection();
        $this->roleConstraints = new ArrayCollection();
        $this->usersHavingAnsweredAnonymously = new ArrayCollection();
        $this->constraints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function isAnonymous(): ?bool
    {
        return $this->anonymous;
    }

    public function setAnonymous(bool $anonymous): self
    {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSurvey($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSurvey() === $this) {
                $question->setSurvey(null);
            }
        }

        return $this;
    }

    public function isEditable(): ?bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): self
    {
        $this->editable = $editable;

        return $this;
    }

    public function getAllowedModifications(): ?int
    {
        return $this->allowedModifications;
    }

    public function setAllowedModifications(?int $allowedModifications): self
    {
        $this->allowedModifications = $allowedModifications;

        return $this;
    }

    /**
     * @return Collection<int, Entry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
            $entry->setSurvey($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getSurvey() === $this) {
                $entry->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Constraint>
     */
    public function getConstraints(): Collection
    {
        return $this->constraints;
    }

    public function addConstraint(Constraint $constraint): self
    {
        if (!$this->constraints->contains($constraint)) {
            $this->constraints->add($constraint);
            $constraint->setSurvey($this);
        }

        return $this;
    }

    public function removeConstraint(Constraint $constraint): self
    {
        if ($this->constraints->removeElement($constraint)) {
            // set the owning side to null (unless already changed)
            if ($constraint->getSurvey() === $this) {
                $constraint->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersHavingAnsweredAnonymously(): Collection
    {
        return $this->usersHavingAnsweredAnonymously;
    }

    public function addUsersHavingAnsweredAnonymously(User $usersHavingAnsweredAnonymously): self
    {
        if (!$this->usersHavingAnsweredAnonymously->contains($usersHavingAnsweredAnonymously)) {
            $this->usersHavingAnsweredAnonymously->add($usersHavingAnsweredAnonymously);
        }

        return $this;
    }

    public function removeUsersHavingAnsweredAnonymously(User $usersHavingAnsweredAnonymously): self
    {
        $this->usersHavingAnsweredAnonymously->removeElement($usersHavingAnsweredAnonymously);

        return $this;
    }
}
