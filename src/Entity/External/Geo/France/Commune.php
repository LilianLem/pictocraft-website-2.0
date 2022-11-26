<?php

namespace App\Entity\External\Geo\France;

use App\Repository\External\Geo\France\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
#[ORM\Table(name: 'geo_france_commune')]
#[UniqueEntity("inseeCode", message: "Ce code INSEE est déjà utilisé")]
class Commune
{
    // Code INSEE sans les lettres (2A/2B dans les codes corses ont été remplacés par 20)
    #[ORM\Id]
    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Range(min: 1001, max: 98999, minMessage: "Le code INSEE numérique (id) doit être un entier supérieur à 1000", maxMessage: "Le code INSEE numérique (id) doit être un entier inférieur à 99000")]
    private ?int $id = null;

    #[ORM\Column(length: 5, unique: true)]
    #[Assert\Length(exactly: 5, exactMessage: "Le code INSEE doit obligatoirement compter 5 caractères")]
    #[Assert\Regex('/0100[1-9]|010[1-9]\d|01[1-9]\d{2}|0[2-9]\d{3}|[13-8]\d{4}|2[AB1-9]\d{3}|9[0-8]\d{3}/', message: "Le format du code INSEE est incorrect. Il doit être compris entre 01001 et 98999, sauf pour la Corse (2A001 à 2B999)")]
    #[Assert\NotBlank]
    private ?string $inseeCode = null;

    #[ORM\ManyToOne(inversedBy: 'communes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Departement $departement = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/[A-Za-zÀàÂâÇçÈèÉéÊêËëÎîÏïÔôŒœÜüÛûŸÿ\- \']+/', message: "Le format du nom est incorrect pour une commune française. Veuillez contacter l'assistance technique")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: CommunePostalData::class, orphanRemoval: true)]
    private Collection $postalData;

    public function __construct()
    {
        $this->postalData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // Ajout de setId pour permettre la définition du champ qui n'est pas auto-incrémenté sur cette entité
    public function setId(int $id): self
    {
        // Interdiction de modifier l'ID s'il est déjà défini (contrainte Foreign Key)
        if(!is_null($this->id)) return $this;

        $this->id = $id;
        return $this;
    }

    public function getInseeCode(): ?string
    {
        return $this->inseeCode;
    }

    public function setInseeCode(string $inseeCode): self
    {
        $this->inseeCode = $inseeCode;

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
     * @return Collection<int, CommunePostalData>
     */
    public function getPostalData(): Collection
    {
        return $this->postalData;
    }

    public function addPostalData(CommunePostalData $postalData): self
    {
        if (!$this->postalData->contains($postalData)) {
            $this->postalData->add($postalData);
            $postalData->setCommune($this);
        }

        return $this;
    }

    public function removePostalData(CommunePostalData $postalData): self
    {
        if ($this->postalData->removeElement($postalData)) {
            // set the owning side to null (unless already changed)
            if ($postalData->getCommune() === $this) {
                $postalData->setCommune(null);
            }
        }

        return $this;
    }
}
