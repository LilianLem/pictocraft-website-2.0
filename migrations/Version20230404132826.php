<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404132826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount ADD apply_automatically TINYINT(1) DEFAULT 0 NOT NULL, CHANGE applies_on applies_on ENUM(\'Commande\', \'Produits éligibles\', \'Produit éligible le moins cher\', \'Produit éligible le plus cher\') NOT NULL COMMENT \'(DC2Enum:fb6cac1caef8bcabe279882e1dde97ed)(DC2Type:discount_applies_on_enum)\'');
        $this->addSql('CREATE UNIQUE INDEX payment_status_unique ON shop_payment_status (payment_id, status)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount DROP apply_automatically, CHANGE applies_on applies_on ENUM(\'order\', \'product\') NOT NULL COMMENT \'(DC2Enum:f3c65566ddfd559bb4605e1ecd7f2645)(DC2Type:discount_applies_on_enum)\'');
        $this->addSql('DROP INDEX payment_status_unique ON shop_payment_status');
    }
}
