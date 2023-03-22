<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315122522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order_item_status CHANGE status status ENUM(\'Panier actuel\', \'Panier abandonné\', \'En attente de paiement\', \'Contacté pour livraison\', \'Expédié\', \'Livraison planifiée\', \'Livraison en cours\', \'Livré\', \'Retourné à l\\\'expéditeur\', \'Demande de rétractation envoyée\', \'Demande de rétractation validée\', \'Demande de rétractation refusée\', \'Demande de retour envoyée\', \'Demande de retour acceptée\', \'Demande de retour refusée\', \'Retour en attente\', \'Retour en cours\', \'Retour reçu\', \'Retour confirmé\', \'Retour non-conforme\', \'Remboursement en attente\', \'Remboursement effectué\', \'Annulé\', \'Demande envoyée\', \'Activation en attente\', \'Activé\', \'Activé partiellement\', \'Activé partiellement (Discord uniquement)\', \'Activé partiellement (Minecraft uniquement)\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:f412f0c18e7a97db48a6f901bedec96c)(DC2Type:order_item_status_enum)\'');
        $this->addSql('ALTER TABLE shop_order_status CHANGE status status ENUM(\'Panier actuel\', \'Panier abandonné\', \'En attente de paiement\', \'Confirmée\', \'Annulée\', \'Expirée\', \'Abandonnée\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:8e2e364cac689ae77e1e6b1694bc9709)(DC2Type:order_status_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order_item_status CHANGE status status ENUM(\'En attente de paiement\', \'Contacté pour livraison\', \'Expédié\', \'Livraison planifiée\', \'Livraison en cours\', \'Livré\', \'Livré partiellement\', \'Retourné à l\\\'expéditeur\', \'Demande de rétractation envoyée\', \'Demande de rétractation validée\', \'Demande de rétractation refusée\', \'Demande de retour envoyée\', \'Demande de retour acceptée\', \'Demande de retour refusée\', \'Retour en attente\', \'Retour en cours\', \'Retour reçu\', \'Retour confirmé\', \'Retour non-conforme\', \'Remboursement en attente\', \'Remboursement effectué\', \'Annulé\', \'Demande envoyée\', \'Activation en attente\', \'Activé\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:a3a2103233690aa921d583f373bda13c)(DC2Type:order_item_status_enum)\'');
        $this->addSql('ALTER TABLE shop_order_status CHANGE status status ENUM(\'Panier actuel\', \'En attente de paiement\', \'Confirmée\', \'Annulée\', \'Expirée\', \'Abandonnée\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:1be8abf241d55a0cae52631f1943f2ea)(DC2Type:order_status_enum)\'');
    }
}
