<?php

namespace App\Entity\Core\Notification;

use App\Entity\Core\User\User;
use App\Repository\Core\Notification\NotificationUserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificationUserRepository::class)]
class NotificationUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notificationsSent')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Notification $notification = null;

    #[ORM\Column(nullable: true)]
    private array $placeholdersContent = [];

    #[ORM\Column(nullable: true)]
    private array $routeParameters = [];

    #[ORM\Column]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $generatedAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sentByEmail = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $sentByDiscordPrivately = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $markedAsReadOnWebsiteAt = null;

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

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getPlaceholdersContent(): array
    {
        return $this->placeholdersContent;
    }

    public function setPlaceholdersContent(?array $placeholdersContent): self
    {
        $this->placeholdersContent = $placeholdersContent;

        return $this;
    }

    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(?array $routeParameters): self
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    public function getGeneratedAt(): ?DateTimeImmutable
    {
        return $this->generatedAt;
    }

    public function setGeneratedAt(DateTimeImmutable $generatedAt): self
    {
        $this->generatedAt = $generatedAt;

        return $this;
    }

    public function isSentByEmail(): ?bool
    {
        return $this->sentByEmail;
    }

    public function setSentByEmail(bool $sentByEmail): self
    {
        $this->sentByEmail = $sentByEmail;

        return $this;
    }

    public function isSentByDiscordPrivately(): ?bool
    {
        return $this->sentByDiscordPrivately;
    }

    public function setSentByDiscordPrivately(bool $sentByDiscordPrivately): self
    {
        $this->sentByDiscordPrivately = $sentByDiscordPrivately;

        return $this;
    }

    public function getMarkedAsReadOnWebsiteAt(): ?DateTimeImmutable
    {
        return $this->markedAsReadOnWebsiteAt;
    }

    public function setMarkedAsReadOnWebsiteAt(?DateTimeImmutable $markedAsReadOnWebsiteAt): self
    {
        $this->markedAsReadOnWebsiteAt = $markedAsReadOnWebsiteAt;

        return $this;
    }
}
