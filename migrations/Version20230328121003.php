<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328121003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order ADD total_amount_ht INT UNSIGNED DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_order_item CHANGE total_price_ttc total_amount_ttc INT UNSIGNED DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_payment_status CHANGE status status ENUM(\'En attente de paiement\', \'Validé\', \'Échoué\', \'Annulé\', \'Remboursé totalement (sur moyen de paiement)\', \'Remboursé partiellement (sur moyen de paiement)\', \'Remboursé totalement (sur porte-monnaie)\') NOT NULL COMMENT \'(DC2Enum:802f08f50f2c8737ed47d902a7f741f6)(DC2Type:payment_status_enum)\'');
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D2754C3A3BB');
        $this->addSql('DROP INDEX UNIQ_6460D2754C3A3BB ON shop_wallet_transaction');
        $this->addSql('ALTER TABLE shop_wallet_transaction CHANGE payment_id generated_discount_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D275B725978A FOREIGN KEY (generated_discount_id) REFERENCES shop_discount (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6460D275B725978A ON shop_wallet_transaction (generated_discount_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order DROP total_amount_ht');
        $this->addSql('ALTER TABLE shop_order_item CHANGE total_amount_ttc total_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE shop_payment_status CHANGE status status ENUM(\'En attente de paiement\', \'Validé\', \'Échoué\', \'Annulé\', \'Remboursé totalement (sur moyen de paiement)\', \'Remboursé partiellement (sur moyen de paiement)\', \'Remboursé totalement (en avoir)\') NOT NULL COMMENT \'(DC2Enum:a91fa3baa0c61135c8dfe37e72757a19)(DC2Type:payment_status_enum)\'');
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D275B725978A');
        $this->addSql('DROP INDEX UNIQ_6460D275B725978A ON shop_wallet_transaction');
        $this->addSql('ALTER TABLE shop_wallet_transaction CHANGE generated_discount_id payment_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D2754C3A3BB FOREIGN KEY (payment_id) REFERENCES shop_payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6460D2754C3A3BB ON shop_wallet_transaction (payment_id)');
    }
}
