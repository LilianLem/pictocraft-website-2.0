<?php

namespace App\Entity\Shop\Payment;

enum StatusEnum: string
{
    case PENDING = "En attente de paiement";
    case VALIDATED = "Validé";

    case ABORTED = "Abandonné";

    case REFUNDED_ENTIRELY = "Remboursé totalement (sur moyen de paiement)";
    case REFUNDED_PARTIALLY = "Remboursé partiellement (sur moyen de paiement)";
    case REFUNDED_ENTIRELY_ON_WALLET = "Remboursé totalement (sur porte-monnaie)";
    case REFUNDED_PARTIALLY_ON_WALLET = "Remboursé partiellement (sur porte-monnaie)";
}
