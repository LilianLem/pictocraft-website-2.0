<?php

namespace App\Entity\Shop\Order;

enum StatusEnum: string
{
    case PAYMENT_PENDING = "En attente de paiement";
    case PAYMENT_FAILED = "Paiement échoué";
    case PAYMENT_CANCELLED = "Paiement annulé";
    case PAYMENT_DONE = "Payé";

    case ORDER_CANCELLED = "Commande annulée";
    case ORDER_EXPIRED = "Commande expirée";
    case ORDER_ABORTED = "Commande abandonnée";

    case INFO_NEEDED = "En attente d'informations";
}
