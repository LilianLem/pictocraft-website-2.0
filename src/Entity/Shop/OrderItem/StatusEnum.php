<?php

namespace App\Entity\Shop\OrderItem;

enum StatusEnum: string
{
    case CART_CURRENT = "Panier actuel";
    case CART_ABORTED = "Panier abandonné";

    case PAYMENT_PENDING = "En attente de paiement";

    case DELIVERY_CLIENT_CONTACTED = "Contacté pour livraison";
    case DELIVERY_SHIPPED = "Expédié";
    case DELIVERY_PLANNED = "Livraison planifiée"; // À n'utiliser que si l'expédition ne passe pas par un transporteur, sinon le suivi du transporteur remplace ce statut
    case DELIVERY_ONGOING = "Livraison en cours"; // À n'utiliser que si l'expédition ne passe pas par un transporteur, sinon le suivi du transporteur remplace ce statut
    case DELIVERY_DONE = "Livré"; // A utiliser pour les produits physiques ou les produits dématérialisés qui doivent être "livrés" et pas "activés" (ex. : clés de jeux)
    case DELIVERY_RETURNED_TO_SENDER = "Retourné à l'expéditeur";

    case WITHDRAWAL_REQUEST_SENT = "Demande de rétractation envoyée";
    case WITHDRAWAL_REQUEST_ACCEPTED = "Demande de rétractation validée";
    case WITHDRAWAL_REQUEST_REJECTED = "Demande de rétractation refusée";
    case RETURN_REQUEST_SENT = "Demande de retour envoyée";
    case RETURN_REQUEST_ACCEPTED = "Demande de retour acceptée";
    case RETURN_REQUEST_REJECTED = "Demande de retour refusée";

    // À utiliser pour les rétractations et les demandes de retour
    case RETURN_PENDING = "Retour en attente";
    case RETURN_IN_PROGRESS = "Retour en cours";
    case RETURN_RECEIVED = "Retour reçu";
    case RETURN_CONFIRMED = "Retour confirmé";
    case RETURN_NON_COMPLIANT = "Retour non-conforme";

    case REFUND_PENDING = "Remboursement en attente";
    case REFUND_DONE = "Remboursement effectué";

    case ITEM_CANCELLED = "Annulé";
    case ITEM_REQUEST_SENT = "Demande envoyée"; // Pour les produits à livraison/activation manuelle dans une commande ou lorsque la livraison automatique a échoué
    case ITEM_ACTIVATION_PENDING = "Activation en attente";
    case ITEM_ACTIVATED = "Activé";
    case ITEM_PARTIALLY_ACTIVATED = "Activé partiellement";
    case ITEM_PARTIALLY_ACTIVATED_DISCORD = "Activé partiellement (Discord uniquement)";
    case ITEM_PARTIALLY_ACTIVATED_MINECRAFT = "Activé partiellement (Minecraft uniquement)";

    case INFO_NEEDED = "En attente d'informations";
}
