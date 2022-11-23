<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117225556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(32) NOT NULL, internal_name VARCHAR(32) NOT NULL, visible TINYINT(1) DEFAULT 0 NOT NULL, color VARCHAR(6) DEFAULT NULL, INDEX IDX_57698A6A727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A727ACA70 FOREIGN KEY (parent_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD relative_user_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E7FBF51B FOREIGN KEY (relative_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E7FBF51B ON user (relative_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A727ACA70');
        $this->addSql('DROP TABLE role');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E7FBF51B');
        $this->addSql('DROP INDEX IDX_8D93D649E7FBF51B ON user');
        $this->addSql('ALTER TABLE user DROP relative_user_id');
    }
}
