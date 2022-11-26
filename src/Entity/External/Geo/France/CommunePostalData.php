<?php

namespace App\Entity\External\Geo\France;

use App\Entity\Core\User\Settings;
use App\Repository\External\Geo\France\CommunePostalDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommunePostalDataRepository::class)]
#[ORM\Table(name: 'geo_france_commune_postal_data')]
#[ORM\UniqueConstraint("postal_data_unique", columns: ["commune_id", "postal_code", "hamlet"])]
#[UniqueEntity(
    fields: ["commune", "postalCode", "hamlet"],
    errorPath: "postalCode",
    message: "Cette combinaison (commune / code postal / hameau) existe déjà",
)]
class CommunePostalData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'postalData')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Commune $commune = null;

    // Stocké en string et pas en int en raison de l'usage qui en sera fait dans le code
    #[ORM\Column(length: 5)]
    #[Assert\Length(exactly: 5, exactMessage: "Le code postal doit comporter exactement 5 chiffres. Pour les codes postaux inférieurs à 10000, ajoutez un zéro au début si ce n'est pas déjà le cas")]
    #[Assert\Regex('/0[1-9]\d{3}|[1-8]\d{4}|9[0-8]\d{3}/', message: "Le format du code postal est incorrect. Il doit être compris entre 01000 et 98999")]
    #[Assert\NotBlank]
    private ?string $postalCode = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom du lieu-dit ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/[A-Za-zÀàÂâÇçÈèÉéÊêËëÎîÏïÔôŒœÜüÛûŸÿ\- \']+/', message: "Le format du nom du lieu-dit est incorrect en toponymie française. Veuillez contacter l'assistance technique")]
    private ?string $hamlet = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 9, nullable: true)]
    #[Assert\Range(min: -90, max: 90, minMessage: "La latitude doit être supérieure ou égale à {{ limit }}°", maxMessage: "La latitude doit être inférieure ou égale à {{ limit }}°")]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 9, nullable: true)]
    #[Assert\Range(min: -180, max: 180, minMessage: "La longitude doit être supérieure ou égale à {{ limit }}°", maxMessage: "La longitude doit être inférieure ou égale à {{ limit }}°")]
    private ?string $longitude = null;

    #[ORM\OneToMany(mappedBy: 'addressCommunePostalData', targetEntity: Settings::class)]
    private Collection $usersWithAddressHere;

    #[ORM\OneToMany(mappedBy: 'ss_addressCommunePostalData', targetEntity: Settings::class)]
    private Collection $ss_usersWithAddressHere;

    public function __construct()
    {
        $this->usersWithAddressHere = new ArrayCollection();
        $this->ss_usersWithAddressHere = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getHamlet(): ?string
    {
        return $this->hamlet;
    }

    public function setHamlet(?string $hamlet): self
    {
        $this->hamlet = $hamlet;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Settings>
     */
    public function getUsersWithAddressHere(): Collection
    {
        return $this->usersWithAddressHere;
    }

    public function addUserWithAddressHere(Settings $user): self
    {
        if (!$this->usersWithAddressHere->contains($user)) {
            $this->usersWithAddressHere->add($user);
            $user->setAddressCommunePostalData($this);
        }

        return $this;
    }

    public function removeUserWithAddressHere(Settings $user): self
    {
        if ($this->usersWithAddressHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAddressCommunePostalData() === $this) {
                $user->setAddressCommunePostalData(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Settings>
     */
    public function getSsUsersWithAddressHere(): Collection
    {
        return $this->ss_usersWithAddressHere;
    }

    public function addSsUserWithAddressHere(Settings $user): self
    {
        if (!$this->ss_usersWithAddressHere->contains($user)) {
            $this->ss_usersWithAddressHere->add($user);
            $user->setSsAddressCommunePostalData($this);
        }

        return $this;
    }

    public function removeSsUserWithAddressHere(Settings $user): self
    {
        if ($this->ss_usersWithAddressHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSsAddressCommunePostalData() === $this) {
                $user->setSsAddressCommunePostalData(null);
            }
        }

        return $this;
    }
}
