<?php

namespace App\Entity\Shop;

use App\Repository\Shop\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AttributeRepository::class)]
#[ORM\Table(name: 'shop_attribute')]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(min: 3, max: 64, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $hidden = null;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: AttributeValue::class, orphanRemoval: true)]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
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

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection<int, AttributeValue>
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(AttributeValue $attributeValue): self
    {
        if (!$this->attributeValues->contains($attributeValue)) {
            $this->attributeValues->add($attributeValue);
            $attributeValue->setAttribute($this);
        }

        return $this;
    }

    public function removeAttributeValue(AttributeValue $attributeValue): self
    {
        if ($this->attributeValues->removeElement($attributeValue)) {
            // set the owning side to null (unless already changed)
            if ($attributeValue->getAttribute() === $this) {
                $attributeValue->setAttribute(null);
            }
        }

        return $this;
    }
}
