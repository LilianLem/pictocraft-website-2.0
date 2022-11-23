<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118184318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_wallet_transaction (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, order_id INT UNSIGNED DEFAULT NULL, description VARCHAR(64) DEFAULT NULL, amount INT NOT NULL, balance INT UNSIGNED DEFAULT 0 NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6460D275A76ED395 (user_id), UNIQUE INDEX UNIQ_6460D2758D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D275A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_wallet_transaction ADD CONSTRAINT FK_6460D2758D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE user DROP shop_balance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D275A76ED395');
        $this->addSql('ALTER TABLE shop_wallet_transaction DROP FOREIGN KEY FK_6460D2758D9F6D38');
        $this->addSql('DROP TABLE shop_wallet_transaction');
        $this->addSql('ALTER TABLE user ADD shop_balance INT UNSIGNED DEFAULT 0 NOT NULL');
    }
}
