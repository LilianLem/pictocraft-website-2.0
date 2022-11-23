<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119205149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA6BF700BD');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F6BF700BD');
        $this->addSql('CREATE TABLE shop_order_item_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_item_id INT UNSIGNED NOT NULL, status ENUM(\'En attente de paiement\', \'Contacté pour livraison\', \'Expédié\', \'Livraison planifiée\', \'Livraison en cours\', \'Livré\', \'Retourné à l\\\'expéditeur\', \'Demande de rétractation envoyée\', \'Demande de rétractation validée\', \'Demande de rétractation refusée\', \'Demande de retour envoyée\', \'Demande de retour acceptée\', \'Demande de retour refusée\', \'Retour en attente\', \'Retour en cours\', \'Retour reçu\', \'Retour confirmé\', \'Retour non-conforme\', \'Remboursement en attente\', \'Remboursement effectué\', \'Annulé\', \'Demande envoyée\', \'Activation en attente\', \'Activé\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:3578033da0f0f73b0bbe228fbbd12657)(DC2Type:order_item_status_enum)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A0CB148E415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_order_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED NOT NULL, status ENUM(\'En attente de paiement\', \'Paiement échoué\', \'Paiement annulé\', \'Payé\', \'Commande annulée\', \'Commande expirée\', \'Commande abandonnée\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:820c370c2849f6fda0c6f5dc86e5ec7e)(DC2Type:order_status_enum)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A5EA125B8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_order_item_status ADD CONSTRAINT FK_5A0CB148E415FB15 FOREIGN KEY (order_item_id) REFERENCES shop_order_item (id)');
        $this->addSql('ALTER TABLE shop_order_status ADD CONSTRAINT FK_A5EA125B8D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('DROP TABLE shop_status');
        $this->addSql('DROP INDEX IDX_323FC9CA6BF700BD ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP status_id');
        $this->addSql('DROP INDEX IDX_2899F22F6BF700BD ON shop_order_item');
        $this->addSql('ALTER TABLE shop_order_item ADD delivery_tracking_link VARCHAR(255) DEFAULT NULL, DROP status_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, order_compatible TINYINT(1) NOT NULL, order_item_compatible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE shop_order_item_status DROP FOREIGN KEY FK_5A0CB148E415FB15');
        $this->addSql('ALTER TABLE shop_order_status DROP FOREIGN KEY FK_A5EA125B8D9F6D38');
        $this->addSql('DROP TABLE shop_order_item_status');
        $this->addSql('DROP TABLE shop_order_status');
        $this->addSql('ALTER TABLE shop_order ADD status_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA6BF700BD FOREIGN KEY (status_id) REFERENCES shop_status (id)');
        $this->addSql('CREATE INDEX IDX_323FC9CA6BF700BD ON shop_order (status_id)');
        $this->addSql('ALTER TABLE shop_order_item ADD status_id INT UNSIGNED NOT NULL, DROP delivery_tracking_link');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F6BF700BD FOREIGN KEY (status_id) REFERENCES shop_status (id)');
        $this->addSql('CREATE INDEX IDX_2899F22F6BF700BD ON shop_order_item (status_id)');
    }
}
