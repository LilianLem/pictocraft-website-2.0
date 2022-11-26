<?php

namespace App\Entity\Shop\RedemptionCode;

use App\Repository\Shop\RedemptionCode\RedemptionCodeAccessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RedemptionCodeAccessRepository::class)]
#[ORM\Table(name: 'shop_redemption_code_access')]
#[UniqueEntity("name", message: "Ce nom est déjà utilisé")]
class RedemptionCodeAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'access', targetEntity: RedemptionCode::class)]
    private Collection $redemptionCodes;

    public function __construct()
    {
        $this->redemptionCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, RedemptionCode>
     */
    public function getRedemptionCodes(): Collection
    {
        return $this->redemptionCodes;
    }

    public function addRedemptionCode(RedemptionCode $redemptionCode): self
    {
        if (!$this->redemptionCodes->contains($redemptionCode)) {
            $this->redemptionCodes->add($redemptionCode);
            $redemptionCode->setAccess($this);
        }

        return $this;
    }

    public function removeRedemptionCode(RedemptionCode $redemptionCode): self
    {
        if ($this->redemptionCodes->removeElement($redemptionCode)) {
            // set the owning side to null (unless already changed)
            if ($redemptionCode->getAccess() === $this) {
                $redemptionCode->setAccess(null);
            }
        }

        return $this;
    }
}
