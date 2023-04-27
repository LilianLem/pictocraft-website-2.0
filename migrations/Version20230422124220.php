<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230422124220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_discount_user_history (id INT UNSIGNED AUTO_INCREMENT NOT NULL, discount_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, number_of_uses INT NOT NULL, INDEX IDX_F7C16364C7C611F (discount_id), INDEX IDX_F7C1636A76ED395 (user_id), UNIQUE INDEX discount_user_history_unique (discount_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_discount_user_history ADD CONSTRAINT FK_F7C16364C7C611F FOREIGN KEY (discount_id) REFERENCES shop_discount (id)');
        $this->addSql('ALTER TABLE shop_discount_user_history ADD CONSTRAINT FK_F7C1636A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_applied_discount ADD conditions LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_discount ADD max_uses_per_user INT UNSIGNED DEFAULT NULL, CHANGE fixed_discount fixed_discount INT UNSIGNED DEFAULT NULL, CHANGE percentage_discount percentage_discount INT UNSIGNED DEFAULT NULL, CHANGE start_at start_at DATETIME DEFAULT NULL, CHANGE priority priority INT DEFAULT 0 NOT NULL, CHANGE applies_on applies_on ENUM(\'1 - Commande\', \'2 - Produits éligibles\', \'3 - Produit éligible le moins cher\', \'4 - Produit éligible le plus cher\') NOT NULL COMMENT \'(DC2Enum:a7439df2130b4c9a2326c0e67552d4a9)(DC2Type:discount_applies_on_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount_user_history DROP FOREIGN KEY FK_F7C16364C7C611F');
        $this->addSql('ALTER TABLE shop_discount_user_history DROP FOREIGN KEY FK_F7C1636A76ED395');
        $this->addSql('DROP TABLE shop_discount_user_history');
        $this->addSql('ALTER TABLE shop_applied_discount DROP conditions');
        $this->addSql('ALTER TABLE shop_discount DROP max_uses_per_user, CHANGE fixed_discount fixed_discount INT DEFAULT NULL, CHANGE percentage_discount percentage_discount INT DEFAULT NULL, CHANGE start_at start_at DATETIME NOT NULL, CHANGE priority priority INT DEFAULT NULL, CHANGE applies_on applies_on ENUM(\'Commande\', \'Produits éligibles\', \'Produit éligible le moins cher\', \'Produit éligible le plus cher\') NOT NULL COMMENT \'(DC2Enum:fb6cac1caef8bcabe279882e1dde97ed)(DC2Type:discount_applies_on_enum)\'');
    }
}
