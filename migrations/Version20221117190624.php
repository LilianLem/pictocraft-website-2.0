<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117190624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, division_id INT UNSIGNED NOT NULL, role ENUM(\'Dirigeant\', \'Gérant\', \'Assistant\') NOT NULL COMMENT \'(DC2Enum:74988ed5734940d41eae2d75ece2b138)(DC2Type:organization_role_enum)\', INDEX IDX_6E60B4F7A76ED395 (user_id), INDEX IDX_6E60B4F741859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_role_division (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(64) NOT NULL, INDEX IDX_27B456B5727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_role ADD CONSTRAINT FK_6E60B4F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organization_role ADD CONSTRAINT FK_6E60B4F741859289 FOREIGN KEY (division_id) REFERENCES organization_role_division (id)');
        $this->addSql('ALTER TABLE organization_role_division ADD CONSTRAINT FK_27B456B5727ACA70 FOREIGN KEY (parent_id) REFERENCES organization_role_division (id)');
        $this->addSql('ALTER TABLE user DROP organization_roles, CHANGE voting_code voting_code VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE user_settings ADD city VARCHAR(64) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_role DROP FOREIGN KEY FK_6E60B4F7A76ED395');
        $this->addSql('ALTER TABLE organization_role DROP FOREIGN KEY FK_6E60B4F741859289');
        $this->addSql('ALTER TABLE organization_role_division DROP FOREIGN KEY FK_27B456B5727ACA70');
        $this->addSql('DROP TABLE organization_role');
        $this->addSql('DROP TABLE organization_role_division');
        $this->addSql('ALTER TABLE user ADD organization_roles VARCHAR(255) DEFAULT NULL, CHANGE voting_code voting_code INT NOT NULL');
        $this->addSql('ALTER TABLE user_settings DROP city');
    }
}
