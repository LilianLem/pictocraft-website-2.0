<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329174017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357977A66F6');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357977A66F6 FOREIGN KEY (default_vat_rate_id) REFERENCES vat_rate (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_payment ADD refunded_amount INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_payment_status CHANGE status status ENUM(\'En attente de paiement\', \'Validé\', \'Abandonné\', \'Remboursé totalement (sur moyen de paiement)\', \'Remboursé partiellement (sur moyen de paiement)\', \'Remboursé totalement (sur porte-monnaie)\', \'Remboursé partiellement (sur porte-monnaie)\') NOT NULL COMMENT \'(DC2Enum:f21f06743f2f090b18c94ddd437f04ac)(DC2Type:payment_status_enum)\'');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448743897540');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448743897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357977A66F6');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357977A66F6 FOREIGN KEY (default_vat_rate_id) REFERENCES vat_rate (id)');
        $this->addSql('ALTER TABLE shop_payment DROP refunded_amount');
        $this->addSql('ALTER TABLE shop_payment_status CHANGE status status ENUM(\'En attente de paiement\', \'Validé\', \'Échoué\', \'Annulé\', \'Remboursé totalement (sur moyen de paiement)\', \'Remboursé partiellement (sur moyen de paiement)\', \'Remboursé totalement (sur porte-monnaie)\') NOT NULL COMMENT \'(DC2Enum:802f08f50f2c8737ed47d902a7f741f6)(DC2Type:payment_status_enum)\'');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448743897540');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448743897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id)');
    }
}
