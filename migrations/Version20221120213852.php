<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221120213852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL FIRST, ADD start_at DATETIME DEFAULT NULL, ADD end_at DATETIME DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD followed_by_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F3970CDB6 FOREIGN KEY (followed_by_id) REFERENCES shop_order_item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2899F22F3970CDB6 ON shop_order_item (followed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_user MODIFY id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('DROP INDEX `PRIMARY` ON role_user');
        $this->addSql('ALTER TABLE role_user DROP id, DROP start_at, DROP end_at');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD PRIMARY KEY (role_id, user_id)');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F3970CDB6');
        $this->addSql('DROP INDEX UNIQ_2899F22F3970CDB6 ON shop_order_item');
        $this->addSql('ALTER TABLE shop_order_item DROP followed_by_id');
    }
}
