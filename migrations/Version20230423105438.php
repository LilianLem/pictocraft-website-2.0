<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423105438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_discount_forbidden_combination (id INT UNSIGNED AUTO_INCREMENT NOT NULL, discount1_id INT UNSIGNED NOT NULL, discount2_id INT UNSIGNED NOT NULL, INDEX IDX_77CAA5025B285C07 (discount1_id), INDEX IDX_77CAA502499DF3E9 (discount2_id), UNIQUE INDEX discount_forbidden_combination_unique (discount1_id, discount2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_discount_forbidden_combination ADD CONSTRAINT FK_77CAA5025B285C07 FOREIGN KEY (discount1_id) REFERENCES shop_discount (id)');
        $this->addSql('ALTER TABLE shop_discount_forbidden_combination ADD CONSTRAINT FK_77CAA502499DF3E9 FOREIGN KEY (discount2_id) REFERENCES shop_discount (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_discount_forbidden_combination DROP FOREIGN KEY FK_77CAA5025B285C07');
        $this->addSql('ALTER TABLE shop_discount_forbidden_combination DROP FOREIGN KEY FK_77CAA502499DF3E9');
        $this->addSql('DROP TABLE shop_discount_forbidden_combination');
    }
}
