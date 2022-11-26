<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126193235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_product_attribute_value (product_id INT UNSIGNED NOT NULL, value_id INT UNSIGNED NOT NULL, INDEX IDX_8FCE09924584665A (product_id), INDEX IDX_8FCE0992F920BBA2 (value_id), PRIMARY KEY(product_id, value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product_attribute_value ADD CONSTRAINT FK_8FCE09924584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_attribute_value ADD CONSTRAINT FK_8FCE0992F920BBA2 FOREIGN KEY (value_id) REFERENCES shop_attribute_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F4584665A');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F65A22152');
        $this->addSql('DROP TABLE product_attribute_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_attribute_value (product_id INT UNSIGNED NOT NULL, attribute_value_id INT UNSIGNED NOT NULL, INDEX IDX_CCC4BE1F4584665A (product_id), INDEX IDX_CCC4BE1F65A22152 (attribute_value_id), PRIMARY KEY(product_id, attribute_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F65A22152 FOREIGN KEY (attribute_value_id) REFERENCES shop_attribute_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_attribute_value DROP FOREIGN KEY FK_8FCE09924584665A');
        $this->addSql('ALTER TABLE shop_product_attribute_value DROP FOREIGN KEY FK_8FCE0992F920BBA2');
        $this->addSql('DROP TABLE shop_product_attribute_value');
    }
}
