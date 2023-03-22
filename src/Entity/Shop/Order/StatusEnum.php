<?php

namespace App\Entity\Shop\Order;

enum StatusEnum: string
{
    case CART_CURRENT = "Panier actuel";
    case CART_ABORTED = "Panier abandonné";

    case PAYMENT_PENDING = "En attente de paiement";
    case ORDER_CONFIRMED = "Confirmée";

    case ORDER_CANCELLED = "Annulée"; # Use only when an order first had Confirmed status (return, withdrawal...)
    case ORDER_EXPIRED = "Expirée"; # Use only after a period of time if nothing happened on a Payment pending order
    case ORDER_ABORTED = "Abandonnée"; # Use only on an order with Payment pending status if it's manually aborted

    case INFO_NEEDED = "En attente d'informations";
}
