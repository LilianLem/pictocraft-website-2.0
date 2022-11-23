<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221120180848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order_item_status CHANGE status status ENUM(\'En attente de paiement\', \'Contacté pour livraison\', \'Expédié\', \'Livraison planifiée\', \'Livraison en cours\', \'Livré partiellement\', \'Livré\', \'Retourné à l\\\'expéditeur\', \'Demande de rétractation envoyée\', \'Demande de rétractation validée\', \'Demande de rétractation refusée\', \'Demande de retour envoyée\', \'Demande de retour acceptée\', \'Demande de retour refusée\', \'Retour en attente\', \'Retour en cours\', \'Retour reçu\', \'Retour confirmé\', \'Retour non-conforme\', \'Remboursement en attente\', \'Remboursement effectué\', \'Annulé\', \'Demande envoyée\', \'Activation en attente\', \'Activé\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:a3a2103233690aa921d583f373bda13c)(DC2Type:order_item_status_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order_item_status CHANGE status status ENUM(\'En attente de paiement\', \'Contacté pour livraison\', \'Expédié\', \'Livraison planifiée\', \'Livraison en cours\', \'Livré\', \'Retourné à l\\\'expéditeur\', \'Demande de rétractation envoyée\', \'Demande de rétractation validée\', \'Demande de rétractation refusée\', \'Demande de retour envoyée\', \'Demande de retour acceptée\', \'Demande de retour refusée\', \'Retour en attente\', \'Retour en cours\', \'Retour reçu\', \'Retour confirmé\', \'Retour non-conforme\', \'Remboursement en attente\', \'Remboursement effectué\', \'Annulé\', \'Demande envoyée\', \'Activation en attente\', \'Activé\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:3578033da0f0f73b0bbe228fbbd12657)(DC2Type:order_item_status_enum)\'');
    }
}
