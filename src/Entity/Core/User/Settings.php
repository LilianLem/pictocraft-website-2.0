<?php

namespace App\Entity\Core\User;

use App\Entity\External\Geo\Country;
use App\Entity\External\Geo\France\CommunePostalData;
use App\Entity\External\Geo\France\Departement;
use App\Repository\Core\User\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
#[ORM\Table(name: 'user_settings')]
class Settings
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'settings', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $user = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $avoidDuplicateGames = null;

    // Département/territoire du domicile principal - Affiché publiquement sur la page de profil
    #[ORM\ManyToOne(inversedBy: 'usersLivingHere')]
    private ?Departement $departement = null;

    // Pays du domicile principal - Affiché publiquement si le département n'est pas renseigné et que le pays n'est pas la France
    #[ORM\ManyToOne(inversedBy: 'usersLivingHere')]
    private ?Country $country = null;

    // ------ Adresse définie par l'utilisateur comme étant celle à utiliser pour la livraison et la facturation ------ \\
    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineBuildingInside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineBuildingOutside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineStreet = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $addressLineHamlet = null;

    #[ORM\ManyToOne(inversedBy: 'usersWithAddressHere')]
    private ?CommunePostalData $addressCommunePostalData = null;

    // Pays d'habitation actuel
    #[ORM\ManyToOne(inversedBy: 'usersWithAddressHere')]
    private ?Country $addressCountry = null;

    // ------ Adresse définie par l'utilisateur comme étant celle à utiliser pour la livraison et la facturation ------ \\

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $ss_addressLineBuildingInside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $ss_addressLineBuildingOutside = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $ss_addressLineStreet = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Une ligne d'adresse ne doit pas dépasser {{ limit }} caractères")]
    private ?string $ss_addressLineHamlet = null;

    #[ORM\ManyToOne(inversedBy: 'ss_usersWithAddressHere')]
    private ?CommunePostalData $ss_addressCommunePostalData = null;

    #[ORM\ManyToOne(inversedBy: 'ss_usersWithAddressHere')]
    private ?Country $ss_addressCountry = null;

    // TODO : implémenter la lib https://github.com/odolbeau/phone-number-bundle
    #[ORM\Column(length: 16, nullable: true)]
    #[Assert\Length(max: 16, maxMessage: "Le numéro de téléphone ne doit pas dépasser {{ limit }} chiffres")]
    #[Assert\Regex('/\+?\d{1,15}/', message: "Le numéro de téléphone est invalide")]
    private ?string $phoneNumber = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isAvoidDuplicateGames(): ?bool
    {
        return $this->avoidDuplicateGames;
    }

    public function setAvoidDuplicateGames(bool $avoidDuplicateGames): self
    {
        $this->avoidDuplicateGames = $avoidDuplicateGames;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAddressLineBuildingInside(): ?string
    {
        return $this->addressLineBuildingInside;
    }

    public function setAddressLineBuildingInside(?string $addressLineBuildingInside): self
    {
        $this->addressLineBuildingInside = $addressLineBuildingInside;

        return $this;
    }

    public function getAddressLineBuildingOutside(): ?string
    {
        return $this->addressLineBuildingOutside;
    }

    public function setAddressLineBuildingOutside(?string $addressLineBuildingOutside): self
    {
        $this->addressLineBuildingOutside = $addressLineBuildingOutside;

        return $this;
    }

    public function getAddressLineStreet(): ?string
    {
        return $this->addressLineStreet;
    }

    public function setAddressLineStreet(?string $addressLineStreet): self
    {
        $this->addressLineStreet = $addressLineStreet;

        return $this;
    }

    public function getAddressLineHamlet(): ?string
    {
        return $this->addressLineHamlet;
    }

    public function setAddressLineHamlet(?string $addressLineHamlet): self
    {
        $this->addressLineHamlet = $addressLineHamlet;

        return $this;
    }

    public function getAddressCommunePostalData(): ?CommunePostalData
    {
        return $this->addressCommunePostalData;
    }

    public function setAddressCommunePostalData(?CommunePostalData $addressCommunePostalData): self
    {
        $this->addressCommunePostalData = $addressCommunePostalData;

        return $this;
    }

    public function getAddressCountry(): ?Country
    {
        return $this->addressCountry;
    }

    public function setAddressCountry(?Country $addressCountry): self
    {
        $this->addressCountry = $addressCountry;

        return $this;
    }

    public function getSsAddressLineBuildingInside(): ?string
    {
        return $this->ss_addressLineBuildingInside;
    }

    public function setSsAddressLineBuildingInside(?string $ss_addressLineBuildingInside): self
    {
        $this->ss_addressLineBuildingInside = $ss_addressLineBuildingInside;

        return $this;
    }

    public function getSsAddressLineBuildingOutside(): ?string
    {
        return $this->ss_addressLineBuildingOutside;
    }

    public function setSsAddressLineBuildingOutside(?string $ss_addressLineBuildingOutside): self
    {
        $this->ss_addressLineBuildingOutside = $ss_addressLineBuildingOutside;

        return $this;
    }

    public function getSsAddressLineStreet(): ?string
    {
        return $this->ss_addressLineStreet;
    }

    public function setSsAddressLineStreet(?string $ss_addressLineStreet): self
    {
        $this->ss_addressLineStreet = $ss_addressLineStreet;

        return $this;
    }

    public function getSsAddressLineHamlet(): ?string
    {
        return $this->ss_addressLineHamlet;
    }

    public function setSsAddressLineHamlet(?string $ss_addressLineHamlet): self
    {
        $this->ss_addressLineHamlet = $ss_addressLineHamlet;

        return $this;
    }

    public function getSsAddressCommunePostalData(): ?CommunePostalData
    {
        return $this->ss_addressCommunePostalData;
    }

    public function setSsAddressCommunePostalData(?CommunePostalData $ss_addressCommunePostalData): self
    {
        $this->ss_addressCommunePostalData = $ss_addressCommunePostalData;

        return $this;
    }

    public function getSsAddressCountry(): ?Country
    {
        return $this->ss_addressCountry;
    }

    public function setSsAddressCountry(?Country $ss_addressCountry): self
    {
        $this->ss_addressCountry = $ss_addressCountry;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
