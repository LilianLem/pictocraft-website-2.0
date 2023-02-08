<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131171813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D12469DE2');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D12469DE2 FOREIGN KEY (category_id) REFERENCES badge_category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE division DROP FOREIGN KEY FK_10174714727ACA70');
        $this->addSql('ALTER TABLE division ADD CONSTRAINT FK_10174714727ACA70 FOREIGN KEY (parent_id) REFERENCES division (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A727ACA70');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A727ACA70 FOREIGN KEY (parent_id) REFERENCES role (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_applied_discount DROP FOREIGN KEY FK_47E877978D9F6D38');
        $this->addSql('ALTER TABLE shop_applied_discount ADD CONSTRAINT FK_47E877978D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_attribute_value DROP FOREIGN KEY FK_17BCBFB6B6E62EFA');
        $this->addSql('ALTER TABLE shop_attribute_value ADD CONSTRAINT FK_17BCBFB6B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357727ACA70');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357727ACA70 FOREIGN KEY (parent_id) REFERENCES shop_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D12469DE2');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D12469DE2 FOREIGN KEY (category_id) REFERENCES badge_category (id)');
        $this->addSql('ALTER TABLE division DROP FOREIGN KEY FK_10174714727ACA70');
        $this->addSql('ALTER TABLE division ADD CONSTRAINT FK_10174714727ACA70 FOREIGN KEY (parent_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A727ACA70');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A727ACA70 FOREIGN KEY (parent_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE shop_applied_discount DROP FOREIGN KEY FK_47E877978D9F6D38');
        $this->addSql('ALTER TABLE shop_applied_discount ADD CONSTRAINT FK_47E877978D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_attribute_value DROP FOREIGN KEY FK_17BCBFB6B6E62EFA');
        $this->addSql('ALTER TABLE shop_attribute_value ADD CONSTRAINT FK_17BCBFB6B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_attribute (id)');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357727ACA70');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357727ACA70 FOREIGN KEY (parent_id) REFERENCES shop_category (id)');
    }
}
