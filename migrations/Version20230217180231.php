<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217180231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount ADD max_discount_amount INT UNSIGNED DEFAULT NULL, ADD max_eligible_item_quantity_in_cart INT UNSIGNED DEFAULT NULL, ADD quantity INT NOT NULL, ADD enabled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD min_quantity INT UNSIGNED DEFAULT NULL, ADD max_quantity INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order_item CHANGE amount quantity INT UNSIGNED DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE shop_product CHANGE amount quantity INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_redemption_code ADD redeemed_by_id INT UNSIGNED DEFAULT NULL, ADD redeemed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE shop_redemption_code ADD CONSTRAINT FK_4ACFB42C2FBC08BA FOREIGN KEY (redeemed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4ACFB42C2FBC08BA ON shop_redemption_code (redeemed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount DROP max_discount_amount, DROP max_eligible_item_quantity_in_cart, DROP quantity, DROP enabled');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP min_quantity, DROP max_quantity');
        $this->addSql('ALTER TABLE shop_order_item CHANGE quantity amount INT UNSIGNED DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE shop_product CHANGE quantity amount INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_redemption_code DROP FOREIGN KEY FK_4ACFB42C2FBC08BA');
        $this->addSql('DROP INDEX IDX_4ACFB42C2FBC08BA ON shop_redemption_code');
        $this->addSql('ALTER TABLE shop_redemption_code DROP redeemed_by_id, DROP redeemed_at');
    }
}
