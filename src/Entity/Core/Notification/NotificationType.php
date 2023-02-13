<?php

namespace App\Entity\Core\Notification;

use App\Entity\Core\ColorEnum;
use App\Repository\Core\Notification\NotificationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificationTypeRepository::class)]
#[UniqueEntity("name", message: "Ce type de notification existe déjà")]
class NotificationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le nom interne ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $internalName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La description interne ne doit pas dépasser {{ limit }} caractères")]
    private ?string $internalDescription = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom de la route ne doit pas dépasser {{ limit }} caractères")]
    private ?string $route = null;

    #[ORM\Column(type: "color_enum", options: ["default" => ColorEnum::PRIMARY])]
    #[Assert\NotBlank]
    private ?ColorEnum $color = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom de l'icône ne doit pas dépasser {{ limit }} caractères")]
    private ?string $icon = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sendOnWebsite = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sendByEmail = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sendByDiscordPrivately = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sendByDiscordPublicly = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le titre sur le site ne doit pas dépasser {{ limit }} caractères")]
    private ?string $titleForWebsite = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le titre du mail ne doit pas dépasser {{ limit }} caractères")]
    private ?string $titleForEmail = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le titre du message privé Discord ne doit pas dépasser {{ limit }} caractères")]
    private ?string $titleForDiscordPrivately = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le titre du message Discord public ne doit pas dépasser {{ limit }} caractères")]
    private ?string $titleForDiscordPublicly = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 500, maxMessage: "Le texte sur le site ne doit pas dépasser {{ limit }} caractères")]
    private ?string $textForWebsite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000, maxMessage: "Le texte brut du mail ne doit pas dépasser {{ limit }} caractères")]
    private ?string $textForEmail_raw = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000, maxMessage: "Le texte HTML du mail ne doit pas dépasser {{ limit }} caractères")]
    private ?string $textForEmail_html = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1500, maxMessage: "Le texte du message privé Discord ne doit pas dépasser {{ limit }} caractères")]
    private ?string $textForDiscordPrivately = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1500, maxMessage: "Le texte du message Discord public ne doit pas dépasser {{ limit }} caractères")]
    private ?string $textForDiscordPublicly = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    public function __construct()
    {
        $this->color = ColorEnum::PRIMARY;
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInternalName(): ?string
    {
        return $this->internalName;
    }

    public function setInternalName(string $internalName): self
    {
        $this->internalName = $internalName;

        return $this;
    }

    public function getInternalDescription(): ?string
    {
        return $this->internalDescription;
    }

    public function setInternalDescription(?string $internalDescription): self
    {
        $this->internalDescription = $internalDescription;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getColor(): ?ColorEnum
    {
        return $this->color;
    }

    public function setColor(ColorEnum $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getSendOnWebsite(): ?bool
    {
        return $this->sendOnWebsite;
    }

    public function setSendOnWebsite(bool $sendOnWebsite): self
    {
        $this->sendOnWebsite = $sendOnWebsite;

        return $this;
    }

    public function getSendByEmail(): ?bool
    {
        return $this->sendByEmail;
    }

    public function setSendByEmail(bool $sendByEmail): self
    {
        $this->sendByEmail = $sendByEmail;

        return $this;
    }

    public function getSendByDiscordPrivately(): ?bool
    {
        return $this->sendByDiscordPrivately;
    }

    public function setSendByDiscordPrivately(bool $sendByDiscordPrivately): self
    {
        $this->sendByDiscordPrivately = $sendByDiscordPrivately;

        return $this;
    }

    public function getSendByDiscordPublicly(): ?bool
    {
        return $this->sendByDiscordPublicly;
    }

    public function setSendByDiscordPublicly(bool $sendByDiscordPublicly): self
    {
        $this->sendByDiscordPublicly = $sendByDiscordPublicly;

        return $this;
    }

    public function getTitleForWebsite(): ?string
    {
        return $this->titleForWebsite;
    }

    public function setTitleForWebsite(string $titleForWebsite): self
    {
        $this->titleForWebsite = $titleForWebsite;

        return $this;
    }

    public function getTitleForEmail(): ?string
    {
        return $this->titleForEmail;
    }

    public function setTitleForEmail(?string $titleForEmail): self
    {
        $this->titleForEmail = $titleForEmail;

        return $this;
    }

    public function getTitleForDiscordPrivately(): ?string
    {
        return $this->titleForDiscordPrivately;
    }

    public function setTitleForDiscordPrivately(?string $titleForDiscordPrivately): self
    {
        $this->titleForDiscordPrivately = $titleForDiscordPrivately;

        return $this;
    }

    public function getTitleForDiscordPublicly(): ?string
    {
        return $this->titleForDiscordPublicly;
    }

    public function setTitleForDiscordPublicly(?string $titleForDiscordPublicly): self
    {
        $this->titleForDiscordPublicly = $titleForDiscordPublicly;

        return $this;
    }

    public function getTextForWebsite(): ?string
    {
        return $this->textForWebsite;
    }

    public function setTextForWebsite(?string $textForWebsite): self
    {
        $this->textForWebsite = $textForWebsite;

        return $this;
    }

    public function getTextForEmailRaw(): ?string
    {
        return $this->textForEmail_raw;
    }

    public function setTextForEmailRaw(?string $textForEmail_raw): self
    {
        $this->textForEmail_raw = $textForEmail_raw;

        return $this;
    }

    public function getTextForEmailHtml(): ?string
    {
        return $this->textForEmail_html;
    }

    public function setTextForEmailHtml(?string $textForEmail_html): self
    {
        $this->textForEmail_html = $textForEmail_html;

        return $this;
    }

    public function getTextForDiscordPrivately(): ?string
    {
        return $this->textForDiscordPrivately;
    }

    public function setTextForDiscordPrivately(?string $textForDiscordPrivately): self
    {
        $this->textForDiscordPrivately = $textForDiscordPrivately;

        return $this;
    }

    public function getTextForDiscordPublicly(): ?string
    {
        return $this->textForDiscordPublicly;
    }

    public function setTextForDiscordPublicly(?string $textForDiscordPublicly): self
    {
        $this->textForDiscordPublicly = $textForDiscordPublicly;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setType($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getType() === $this) {
                $notification->setType(null);
            }
        }

        return $this;
    }
}
