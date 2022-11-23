<?php

namespace App\Entity\External\Geo;

use App\Entity\Core\UserSettings;
use App\Repository\External\Geo\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ORM\Table(name: 'geo_country')]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // Norme ISO 3166-1 alpha-2
    #[ORM\Column(length: 2, unique: true)]
    #[Assert\Length(exactly: 2, exactMessage: "Le format du code ISO est incorrect. Il doit comporter exactement 2 majuscules (norme ISO 3166-1 alpha-2)")]
    #[Assert\Regex('/[A-Z]{2}/', message: "Le format du code ISO est incorrect. Il doit comporter exactement 2 majuscules (norme ISO 3166-1 alpha-2)")]
    #[Assert\NotBlank]
    private ?string $isoCode_alpha2 = null;

    // Norme ISO 3166-1 alpha-3
    #[ORM\Column(length: 3, unique: true)]
    #[Assert\Length(exactly: 3, exactMessage: "Le format du code ISO est incorrect. Il doit comporter exactement 3 majuscules (norme ISO 3166-1 alpha-3)")]
    #[Assert\Regex('/[A-Z]{3}/', message: "Le format du code ISO est incorrect. Il doit comporter exactement 3 majuscules (norme ISO 3166-1 alpha-3)")]
    #[Assert\NotBlank]
    private ?string $isoCode_alpha3 = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom pour tri alphabétique ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $nameForSorting = null;

    #[ORM\Column(length: 3, nullable: true)]
    #[Assert\Length(max: 3, maxMessage: "L'article ne doit pas dépasser {{ limit }} caractères")]
    private ?string $article = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: UserSettings::class)]
    private Collection $usersLivingHere;

    #[ORM\OneToMany(mappedBy: 'addressCountry', targetEntity: UserSettings::class)]
    private Collection $usersWithAddressHere;

    #[ORM\OneToMany(mappedBy: 'ss_addressCountry', targetEntity: UserSettings::class)]
    private Collection $ss_usersWithAddressHere;

    public function __construct()
    {
        $this->usersLivingHere = new ArrayCollection();
        $this->usersWithAddressHere = new ArrayCollection();
        $this->ss_usersWithAddressHere = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsoCodeAlpha2(): ?string
    {
        return $this->isoCode_alpha2;
    }

    public function setIsoCodeAlpha2(string $isoCode_alpha2): self
    {
        $this->isoCode_alpha2 = $isoCode_alpha2;

        return $this;
    }

    public function getIsoCodeAlpha3(): ?string
    {
        return $this->isoCode_alpha3;
    }

    public function setIsoCodeAlpha3(string $isoCode_alpha3): self
    {
        $this->isoCode_alpha3 = $isoCode_alpha3;

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

    public function getNameForSorting(): ?string
    {
        return $this->nameForSorting;
    }

    public function setNameForSorting(string $nameForSorting): self
    {
        $this->nameForSorting = $nameForSorting;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

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
            $user->setCountry($this);
        }

        return $this;
    }

    public function removeUserLivingHere(UserSettings $user): self
    {
        if ($this->usersLivingHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCountry() === $this) {
                $user->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSettings>
     */
    public function getUsersWithAddressHere(): Collection
    {
        return $this->usersWithAddressHere;
    }

    public function addUserWithAddressHere(UserSettings $user): self
    {
        if (!$this->usersWithAddressHere->contains($user)) {
            $this->usersWithAddressHere->add($user);
            $user->setAddressCountry($this);
        }

        return $this;
    }

    public function removeUserWithAddressHere(UserSettings $user): self
    {
        if ($this->usersWithAddressHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAddressCountry() === $this) {
                $user->setAddressCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSettings>
     */
    public function getSsUsersWithAddressHere(): Collection
    {
        return $this->ss_usersWithAddressHere;
    }

    public function addSsUserWithAddressHere(UserSettings $user): self
    {
        if (!$this->ss_usersWithAddressHere->contains($user)) {
            $this->ss_usersWithAddressHere->add($user);
            $user->setSsAddressCountry($this);
        }

        return $this;
    }

    public function removeSsUserWithAddressHere(UserSettings $user): self
    {
        if ($this->ss_usersWithAddressHere->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSsAddressCountry() === $this) {
                $user->setSsAddressCountry(null);
            }
        }

        return $this;
    }
}
