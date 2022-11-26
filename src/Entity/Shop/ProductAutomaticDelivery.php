<?php

namespace App\Entity\Shop;

use App\Repository\Shop\ProductAutomaticDeliveryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductAutomaticDeliveryRepository::class)]
#[ORM\Table(name: 'shop_product_automatic_delivery')]
class ProductAutomaticDelivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'automaticDeliveryData', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le nom de la classe de livraison automatique ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $className = null;

    #[ORM\Column(nullable: true)]
    private array $settings = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return class-string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    // TODO : renvoyer une erreur si la classe n'existe pas
    public function setClassName(string $className): self
    {
        if(class_exists("App\Entity\Shop\AutomaticDelivery\\$className")) {
            $this->className = $className;
        }

        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }
}
