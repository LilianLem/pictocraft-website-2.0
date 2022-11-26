<?php

namespace App\Entity\External\Geo\France;

use App\Repository\External\Geo\France\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'geo_france_region')]
#[UniqueEntity("inseeCode", message: "Ce code INSEE est déjà utilisé")]
#[UniqueEntity("name", message: "Cette région est déjà renseignée")]
#[UniqueEntity("isoCode", message: "Ce code ISO est déjà utilisé")]
class Region
{
    #[ORM\Id]
    #[ORM\Column(name: "id", options: ["unsigned" => true], unique: true)]
    private ?int $inseeCode = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/[A-Za-zÀàÂâÇçÈèÉéÊêËëÎîÏïÔôŒœÜüÛûŸÿ\- \']+/', message: "Le format du nom est incorrect en toponymie française. Veuillez contacter l'assistance technique")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Departement::class, orphanRemoval: true)]
    private Collection $departements;

    // Norme ISO 3166-2
    #[ORM\Column(length: 3, unique: true)]
    #[Assert\Regex('/[A-Z\d]+/', message: "Le format du code ISO est incorrect. Veuillez contacter l'assistance technique")]
    #[Assert\NotBlank]
    private ?string $isoCode = null;

    public function __construct()
    {
        $this->departements = new ArrayCollection();
    }

    // Méthode ajoutée manuellement pour des raisons de praticité d'utilisation
    public function getId(): ?int
    {
        return $this->getInseeCode();
    }

    // Méthode ajoutée manuellement pour des raisons de praticité d'utilisation, voir commentaire sur setInseeCode()
    public function setId(int $id): self
    {
        return $this->setInseeCode($id);
    }

    public function getInseeCode(): ?int
    {
        return $this->inseeCode;
    }

    // Ajout de setInseeCode (= setId) pour permettre la définition du champ qui n'est pas auto-incrémenté sur cette entité
    public function setInseeCode(int $inseeCode): self
    {
        // Interdiction de modifier l'ID s'il est déjà défini (contrainte Foreign Key)
        if(!is_null($this->inseeCode)) return $this;

        $this->inseeCode = $inseeCode;
        return $this;
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

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
            $departement->setRegion($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getRegion() === $this) {
                $departement->setRegion(null);
            }
        }

        return $this;
    }

    public function getIsoCode(): ?string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }
}
