<?php

namespace App\Entity\External\Geo\France;

use App\Entity\Core\User\UserSettings;
use App\Repository\External\Geo\France\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ORM\Table(name: 'geo_france_departement')]
class Departement
{
    #[ORM\Id]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // Code INSEE = code ISO sur cette entité. Norme ISO 3166-2
    #[ORM\Column(length: 3, unique: true)]
    #[Assert\Length(min: 2, max: 3, minMessage: "Le code INSEE doit comporter au moins {{ limit }} caractères", maxMessage: "Le code INSEE ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/0[1-9]|[13-8]\d|2[AB1-9]|9[0-5]|9[7-8]\d/', message: "Le format du code INSEE est incorrect : il doit être compris entre 01 et 95 ou entre 970 et 989, sauf pour la Corse (2A/2B)")]
    #[Assert\NotBlank]
    private ?string $inseeCode = null;

    #[ORM\ManyToOne(inversedBy: 'departements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Region $region = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/[A-Za-zÀàÂâÇçÈèÉéÊêËëÎîÏïÔôŒœÜüÛûŸÿ\- \']+/', message: "Le format du nom est incorrect en toponymie française. Veuillez contacter l'assistance technique")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: Commune::class, orphanRemoval: true)]
    private Collection $communes;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: UserSettings::class)]
    private Collection $usersLivingHere;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
        $this->usersLivingHere = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
     * @return Collection<int, Commune>
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): self
    {
        if (!$this->communes->contains($commune)) {
            $this->communes->add($commune);
            $commune->setDepartement($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): self
    {
        if ($this->communes->removeElement($commune)) {
            // set the owning side to null (unless already changed)
            if ($commune->getDepartement() === $this) {
                $commune->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSettings>
     */
    public function getUsersLivingHere(): Collection
    {
        return $this->usersLivingHere;
    }

    public function addUserLivingHere(UserSettings $user): self
    {
        if (!$this->usersLivingHere->contains($user)) {
            $this->usersLivingHere->add($user);
            $user->setDepartement($this);
        }

        return $this;
    }

    public function removeUserLivingHere(UserSettings $user): self
    {
        if ($this->usersLivingHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDepartement() === $this) {
                $user->setDepartement(null);
            }
        }

        return $this;
    }
}
