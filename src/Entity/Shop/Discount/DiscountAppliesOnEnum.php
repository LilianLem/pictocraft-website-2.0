<?php

namespace App\Entity\Shop\Discount;

enum DiscountAppliesOnEnum: string
{
    case ORDER = "Commande";
    case ALL_ELIGIBLE_PRODUCTS = "Produits éligibles";
    case CHEAPEST_ELIGIBLE_PRODUCT = "Produit éligible le moins cher";
    case MOST_EXPENSIVE_ELIGIBLE_PRODUCT = "Produit éligible le plus cher";
}
