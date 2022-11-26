<?php

namespace App\Entity\Core\Badge;

use App\Entity\Core\User\User;
use App\Repository\Core\Badge\BadgeUserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BadgeUserRepository::class)]
#[ORM\UniqueConstraint("badge_user_unique", columns: ["badge_id", "user_id"])]
#[UniqueEntity(
    fields: ["badge", "user"],
    errorPath: "badge",
    message: "Cet utilisateur possède déjà ce badge",
)]
class BadgeUser
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'badges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Badge $badge = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $obtainedAt = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getObtainedAt(): ?DateTimeImmutable
    {
        return $this->obtainedAt;
    }

    public function setObtainedAt(DateTimeImmutable $obtainedAt): self
    {
        $this->obtainedAt = $obtainedAt;

        return $this;
    }
}
