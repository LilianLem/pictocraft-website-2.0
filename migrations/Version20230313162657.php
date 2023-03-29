<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313162657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_payment (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED NOT NULL, payment_method_id INT UNSIGNED NOT NULL, amount INT UNSIGNED NOT NULL, token VARCHAR(128) DEFAULT NULL, INDEX IDX_6E1BC4278D9F6D38 (order_id), INDEX IDX_6E1BC4275AA1164F (payment_method_id), UNIQUE INDEX payment_method_order_unique (order_id, payment_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_payment_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, payment_id INT UNSIGNED NOT NULL, status ENUM(\'En attente de paiement\', \'Validé\', \'Échoué\', \'Annulé\', \'Remboursé totalement (sur moyen de paiement)\', \'Remboursé partiellement (sur moyen de paiement)\', \'Remboursé totalement (en avoir)\') NOT NULL COMMENT \'(DC2Enum:a91fa3baa0c61135c8dfe37e72757a19)(DC2Type:payment_status_enum)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) DEFAULT NULL, INDEX IDX_CE99FF8C4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vat_rate (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_F684F7C75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vat_value (id INT UNSIGNED AUTO_INCREMENT NOT NULL, rate_id INT UNSIGNED NOT NULL, value INT UNSIGNED NOT NULL, end_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_475BEFE7BC999F9F (rate_id), UNIQUE INDEX vat_value_unique (rate_id, value, end_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4278D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4275AA1164F FOREIGN KEY (payment_method_id) REFERENCES shop_payment_method (id)');
        $this->addSql('ALTER TABLE shop_payment_status ADD CONSTRAINT FK_CE99FF8C4C3A3BB FOREIGN KEY (payment_id) REFERENCES shop_payment (id)');
        $this->addSql('ALTER TABLE vat_value ADD CONSTRAINT FK_475BEFE7BC999F9F FOREIGN KEY (rate_id) REFERENCES vat_rate (id)');
        $this->addSql('ALTER TABLE shop_category ADD default_vat_rate_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357977A66F6 FOREIGN KEY (default_vat_rate_id) REFERENCES vat_rate (id)');
        $this->addSql('CREATE INDEX IDX_DDF4E357977A66F6 ON shop_category (default_vat_rate_id)');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA5AA1164F');
        $this->addSql('DROP INDEX IDX_323FC9CA5AA1164F ON shop_order');
        $this->addSql('ALTER TABLE shop_order ADD base_subtotal_ttc INT UNSIGNED DEFAULT 0 NOT NULL, ADD total_amount_ttc INT UNSIGNED DEFAULT 0 NOT NULL, DROP payment_method_id, DROP price_ht, DROP price_ttc, DROP paypal_token');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F126F525E');
        $this->addSql('DROP INDEX IDX_2899F22F126F525E ON shop_order_item');
        $this->addSql('DROP INDEX order_item_unique ON shop_order_item');
        $this->addSql('ALTER TABLE shop_order_item ADD base_price_ttc_per_unit INT UNSIGNED DEFAULT 0 NOT NULL, ADD total_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP base_price_ht, DROP base_price_ttc, DROP price_ht, DROP price_ttc, CHANGE item_id product_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('CREATE INDEX IDX_2899F22F4584665A ON shop_order_item (product_id)');
        $this->addSql('CREATE UNIQUE INDEX order_item_unique ON shop_order_item (order_id, product_id)');
        $this->addSql('ALTER TABLE shop_order_status CHANGE status status ENUM(\'Panier actuel\', \'En attente de paiement\', \'Confirmée\', \'Annulée\', \'Expirée\', \'Abandonnée\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:1be8abf241d55a0cae52631f1943f2ea)(DC2Type:order_status_enum)\'');
        $this->addSql('ALTER TABLE shop_product ADD vat_rate_id INT UNSIGNED DEFAULT NULL, DROP base_price_ht, DROP base_price_ttc, DROP price_ht, DROP public_discount_text');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448743897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id)');
        $this->addSql('CREATE INDEX IDX_D079448743897540 ON shop_product (vat_rate_id)');
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D2758D9F6D38');
        $this->addSql('DROP INDEX UNIQ_6460D2758D9F6D38 ON shop_wallet_transaction');
        $this->addSql('ALTER TABLE shop_wallet_transaction CHANGE order_id payment_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D2754C3A3BB FOREIGN KEY (payment_id) REFERENCES shop_payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6460D2754C3A3BB ON shop_wallet_transaction (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D2754C3A3BB');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357977A66F6');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448743897540');
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC4278D9F6D38');
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC4275AA1164F');
        $this->addSql('ALTER TABLE shop_payment_status DROP FOREIGN KEY FK_CE99FF8C4C3A3BB');
        $this->addSql('ALTER TABLE vat_value DROP FOREIGN KEY FK_475BEFE7BC999F9F');
        $this->addSql('DROP TABLE shop_payment');
        $this->addSql('DROP TABLE shop_payment_status');
        $this->addSql('DROP TABLE vat_rate');
        $this->addSql('DROP TABLE vat_value');
        $this->addSql('DROP INDEX IDX_DDF4E357977A66F6 ON shop_category');
        $this->addSql('ALTER TABLE shop_category DROP default_vat_rate_id');
        $this->addSql('ALTER TABLE shop_order ADD payment_method_id INT UNSIGNED NOT NULL, ADD price_ht INT UNSIGNED DEFAULT 0 NOT NULL, ADD price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, ADD paypal_token VARCHAR(20) DEFAULT NULL, DROP base_subtotal_ttc, DROP total_amount_ttc');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA5AA1164F FOREIGN KEY (payment_method_id) REFERENCES shop_payment_method (id)');
        $this->addSql('CREATE INDEX IDX_323FC9CA5AA1164F ON shop_order (payment_method_id)');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F4584665A');
        $this->addSql('DROP INDEX IDX_2899F22F4584665A ON shop_order_item');
        $this->addSql('DROP INDEX order_item_unique ON shop_order_item');
        $this->addSql('ALTER TABLE shop_order_item ADD base_price_ht INT UNSIGNED DEFAULT 0 NOT NULL, ADD base_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, ADD price_ht INT UNSIGNED DEFAULT 0 NOT NULL, ADD price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, DROP base_price_ttc_per_unit, DROP total_price_ttc, DROP created_at, CHANGE product_id item_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F126F525E FOREIGN KEY (item_id) REFERENCES shop_product (id)');
        $this->addSql('CREATE INDEX IDX_2899F22F126F525E ON shop_order_item (item_id)');
        $this->addSql('CREATE UNIQUE INDEX order_item_unique ON shop_order_item (order_id, item_id)');
        $this->addSql('ALTER TABLE shop_order_status CHANGE status status ENUM(\'En attente de paiement\', \'Paiement échoué\', \'Paiement annulé\', \'Payé\', \'Commande annulée\', \'Commande expirée\', \'Commande abandonnée\', \'En attente d\\\'informations\') NOT NULL COMMENT \'(DC2Enum:820c370c2849f6fda0c6f5dc86e5ec7e)(DC2Type:order_status_enum)\'');
        $this->addSql('DROP INDEX IDX_D079448743897540 ON shop_product');
        $this->addSql('ALTER TABLE shop_product ADD base_price_ht INT UNSIGNED DEFAULT 0 NOT NULL, ADD base_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, ADD price_ht INT UNSIGNED DEFAULT 0 NOT NULL, ADD public_discount_text VARCHAR(64) DEFAULT NULL, DROP vat_rate_id');
        $this->addSql('DROP INDEX UNIQ_6460D2754C3A3BB ON shop_wallet_transaction');
        $this->addSql('ALTER TABLE shop_wallet_transaction CHANGE payment_id order_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D2758D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6460D2758D9F6D38 ON shop_wallet_transaction (order_id)');

    }
}
