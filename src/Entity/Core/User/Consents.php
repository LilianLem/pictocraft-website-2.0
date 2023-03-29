<?php

namespace App\Entity\Core\User;

use App\Repository\Core\User\ConsentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsentsRepository::class)]
#[ORM\Table(name: 'user_consents')]
class Consents
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'userConsents', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $statisticalPurposes = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $publicUsername = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $publicDepartement = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $publicAge = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $publicFirstLogin = null;

    // protected = affiché seulement aux membres connectés
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $protectedBirthday = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $readAndAcceptedRules = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $readAndAcceptedPenaltyTerms = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $emailContactPurpose = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $phoneContactPurpose = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $emailServiceProvidersUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $usernameCompliant = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $realPersonalInfo = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $secretSantaAddressUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $mainAddressShopUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $mainAddressOtherUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $minecraftAccountUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $steamAccountUsage = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $discordAccountUsage = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatisticalPurposes(): ?bool
    {
        return $this->statisticalPurposes;
    }

    public function setStatisticalPurposes(bool $statisticalPurposes): self
    {
        $this->statisticalPurposes = $statisticalPurposes;

        return $this;
    }

    public function getPublicUsername(): ?bool
    {
        return $this->publicUsername;
    }

    public function setPublicUsername(bool $publicUsername): self
    {
        $this->publicUsername = $publicUsername;

        return $this;
    }

    public function getPublicDepartement(): ?bool
    {
        return $this->publicDepartement;
    }

    public function setPublicDepartement(bool $publicDepartement): self
    {
        $this->publicDepartement = $publicDepartement;

        return $this;
    }

    public function getPublicAge(): ?bool
    {
        return $this->publicAge;
    }

    public function setPublicAge(bool $publicAge): self
    {
        $this->publicAge = $publicAge;

        return $this;
    }

    public function getPublicFirstLogin(): ?bool
    {
        return $this->publicFirstLogin;
    }

    public function setPublicFirstLogin(bool $publicFirstLogin): self
    {
        $this->publicFirstLogin = $publicFirstLogin;

        return $this;
    }

    public function getProtectedBirthday(): ?bool
    {
        return $this->protectedBirthday;
    }

    public function setProtectedBirthday(bool $protectedBirthday): self
    {
        $this->protectedBirthday = $protectedBirthday;

        return $this;
    }

    public function getReadAndAcceptedRules(): ?bool
    {
        return $this->readAndAcceptedRules;
    }

    public function setReadAndAcceptedRules(bool $readAndAcceptedRules): self
    {
        $this->readAndAcceptedRules = $readAndAcceptedRules;

        return $this;
    }

    public function getReadAndAcceptedPenaltyTerms(): ?bool
    {
        return $this->readAndAcceptedPenaltyTerms;
    }

    public function setReadAndAcceptedPenaltyTerms(bool $readAndAcceptedPenaltyTerms): self
    {
        $this->readAndAcceptedPenaltyTerms = $readAndAcceptedPenaltyTerms;

        return $this;
    }

    public function getEmailContactPurposesUsage(): ?bool
    {
        return $this->emailContactPurpose;
    }

    public function setEmailContactPurpose(bool $emailContactPurpose): self
    {
        $this->emailContactPurpose = $emailContactPurpose;

        return $this;
    }

    public function getPhoneContactPurpose(): ?bool
    {
        return $this->phoneContactPurpose;
    }

    public function setPhoneContactPurpose(bool $phoneContactPurpose): self
    {
        $this->phoneContactPurpose = $phoneContactPurpose;

        return $this;
    }

    public function getEmailServiceProvidersUsage(): ?bool
    {
        return $this->emailServiceProvidersUsage;
    }

    public function setEmailServiceProvidersUsage(bool $emailServiceProvidersUsage): self
    {
        $this->emailServiceProvidersUsage = $emailServiceProvidersUsage;

        return $this;
    }

    public function getUsernameCompliant(): ?bool
    {
        return $this->usernameCompliant;
    }

    public function setUsernameCompliant(bool $usernameCompliant): self
    {
        $this->usernameCompliant = $usernameCompliant;

        return $this;
    }

    public function getRealPersonalInfo(): ?bool
    {
        return $this->realPersonalInfo;
    }

    public function setRealPersonalInfo(bool $realPersonalInfo): self
    {
        $this->realPersonalInfo = $realPersonalInfo;

        return $this;
    }

    public function getSecretSantaAddressUsage(): ?bool
    {
        return $this->secretSantaAddressUsage;
    }

    public function setSecretSantaAddressUsage(bool $secretSantaAddressUsage): self
    {
        $this->secretSantaAddressUsage = $secretSantaAddressUsage;

        return $this;
    }

    public function getMainAddressShopUsage(): ?bool
    {
        return $this->mainAddressShopUsage;
    }

    public function setMainAddressShopUsage(bool $mainAddressShopUsage): self
    {
        $this->mainAddressShopUsage = $mainAddressShopUsage;

        return $this;
    }

    public function getMainAddressOtherUsage(): ?bool
    {
        return $this->mainAddressOtherUsage;
    }

    public function setMainAddressOtherUsage(bool $mainAddressOtherUsage): self
    {
        $this->mainAddressOtherUsage = $mainAddressOtherUsage;

        return $this;
    }

    public function getMinecraftAccountUsage(): ?bool
    {
        return $this->minecraftAccountUsage;
    }

    public function setMinecraftAccountUsage(bool $minecraftAccountUsage): self
    {
        $this->minecraftAccountUsage = $minecraftAccountUsage;

        return $this;
    }

    public function getSteamAccountUsage(): ?bool
    {
        return $this->steamAccountUsage;
    }

    public function setSteamAccountUsage(bool $steamAccountUsage): self
    {
        $this->steamAccountUsage = $steamAccountUsage;

        return $this;
    }

    public function getDiscordAccountUsage(): ?bool
    {
        return $this->discordAccountUsage;
    }

    public function setDiscordAccountUsage(bool $discordAccountUsage): self
    {
        $this->discordAccountUsage = $discordAccountUsage;

        return $this;
    }
}
