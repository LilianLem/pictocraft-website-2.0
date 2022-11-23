<?php

namespace App\Entity\Modules\Survey;

use App\Repository\Modules\Survey\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'survey_question')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Survey $survey = null;

    #[ORM\Column]
    #[Assert\Positive(message: "La position doit être positive")]
    #[Assert\NotBlank]
    private ?int $position = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le sous-titre ne doit pas dépasser {{ limit }} caractères")]
    private ?string $subtitle = null;

    #[ORM\Column(type: "question_field_type_enum")]
    #[Assert\NotBlank]
    private ?QuestionFieldTypeEnum $fieldType = null;

    #[ORM\Column(nullable: true)]
    private array $answerList = [];

    #[ORM\Column(nullable: true)]
    private array $constraints = [];

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private Collection $answers;

    public function __construct()
    {
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getFieldType(): ?QuestionFieldTypeEnum
    {
        return $this->fieldType;
    }

    public function setFieldType(QuestionFieldTypeEnum $fieldType): self
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getAnswerList(): array
    {
        return $this->answerList;
    }

    public function setAnswerList(?array $answerList): self
    {
        $this->answerList = $answerList;

        return $this;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function setConstraints(?array $constraints): self
    {
        $this->constraints = $constraints;

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
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
