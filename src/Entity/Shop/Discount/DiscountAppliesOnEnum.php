<?php

namespace App\Entity\Shop\Discount;

enum DiscountAppliesOnEnum: string
{
    case ORDER = "1 - Commande";
    case ALL_ELIGIBLE_PRODUCTS = "2 - Produits éligibles";
    case CHEAPEST_ELIGIBLE_PRODUCT = "3 - Produit éligible le moins cher";
    case MOST_EXPENSIVE_ELIGIBLE_PRODUCT = "4 - Produit éligible le plus cher";
}
