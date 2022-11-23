<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118230126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_applied_discount (id INT UNSIGNED AUTO_INCREMENT NOT NULL, discount_id INT UNSIGNED NOT NULL, order_id INT UNSIGNED DEFAULT NULL, order_item_id INT UNSIGNED DEFAULT NULL, fixed_discount INT DEFAULT NULL, percentage_discount INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_47E877974C7C611F (discount_id), INDEX IDX_47E877978D9F6D38 (order_id), INDEX IDX_47E87797E415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_discount (id INT UNSIGNED AUTO_INCREMENT NOT NULL, applies_on ENUM(\'order\', \'product\') NOT NULL COMMENT \'(DC2Enum:f3c65566ddfd559bb4605e1ecd7f2645)(DC2Type:discount_applies_on_enum)\', label VARCHAR(32) DEFAULT NULL, private_description VARCHAR(128) DEFAULT NULL, conditions LONGTEXT DEFAULT NULL, code VARCHAR(16) DEFAULT NULL, fixed_discount INT DEFAULT NULL, percentage_discount INT DEFAULT NULL, start_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, priority INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_discount_constraint (id INT UNSIGNED AUTO_INCREMENT NOT NULL, constraint_group_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED DEFAULT NULL, category_id INT UNSIGNED DEFAULT NULL, attribute_value_id INT UNSIGNED DEFAULT NULL, user_id INT UNSIGNED DEFAULT NULL, role_id INT UNSIGNED DEFAULT NULL, min_product_price INT DEFAULT NULL, max_product_price INT DEFAULT NULL, min_order_amount INT DEFAULT NULL, max_order_amount INT DEFAULT NULL, INDEX IDX_6421F553ED2B9C81 (constraint_group_id), INDEX IDX_6421F5534584665A (product_id), INDEX IDX_6421F55312469DE2 (category_id), INDEX IDX_6421F55365A22152 (attribute_value_id), INDEX IDX_6421F553A76ED395 (user_id), INDEX IDX_6421F553D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_discount_constraint_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, discount_id INT UNSIGNED NOT NULL, constraints_needed INT NOT NULL, INDEX IDX_BA66A68A4C7C611F (discount_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_applied_discount ADD CONSTRAINT FK_47E877974C7C611F FOREIGN KEY (discount_id) REFERENCES shop_discount (id)');
        $this->addSql('ALTER TABLE shop_applied_discount ADD CONSTRAINT FK_47E877978D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_applied_discount ADD CONSTRAINT FK_47E87797E415FB15 FOREIGN KEY (order_item_id) REFERENCES shop_order_item (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F553ED2B9C81 FOREIGN KEY (constraint_group_id) REFERENCES shop_discount_constraint_group (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F5534584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F55312469DE2 FOREIGN KEY (category_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F55365A22152 FOREIGN KEY (attribute_value_id) REFERENCES shop_attribute_value (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F553A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint ADD CONSTRAINT FK_6421F553D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE shop_discount_constraint_group ADD CONSTRAINT FK_BA66A68A4C7C611F FOREIGN KEY (discount_id) REFERENCES shop_discount (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_applied_discount DROP FOREIGN KEY FK_47E877974C7C611F');
        $this->addSql('ALTER TABLE shop_applied_discount DROP FOREIGN KEY FK_47E877978D9F6D38');
        $this->addSql('ALTER TABLE shop_applied_discount DROP FOREIGN KEY FK_47E87797E415FB15');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F553ED2B9C81');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F5534584665A');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F55312469DE2');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F55365A22152');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F553A76ED395');
        $this->addSql('ALTER TABLE shop_discount_constraint DROP FOREIGN KEY FK_6421F553D60322AC');
        $this->addSql('ALTER TABLE shop_discount_constraint_group DROP FOREIGN KEY FK_BA66A68A4C7C611F');
        $this->addSql('DROP TABLE shop_applied_discount');
        $this->addSql('DROP TABLE shop_discount');
        $this->addSql('DROP TABLE shop_discount_constraint');
        $this->addSql('DROP TABLE shop_discount_constraint_group');
    }
}
