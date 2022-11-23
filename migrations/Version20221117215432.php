<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117215432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE division (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(64) NOT NULL, INDEX IDX_10174714727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE division_member (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, division_id INT UNSIGNED NOT NULL, role ENUM(\'Dirigeant\', \'Gérant\', \'Assistant\') NOT NULL COMMENT \'(DC2Enum:74988ed5734940d41eae2d75ece2b138)(DC2Type:division_role_enum)\', INDEX IDX_8AC283FFA76ED395 (user_id), INDEX IDX_8AC283FF41859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE division ADD CONSTRAINT FK_10174714727ACA70 FOREIGN KEY (parent_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE division_member ADD CONSTRAINT FK_8AC283FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE division_member ADD CONSTRAINT FK_8AC283FF41859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE organization_role DROP FOREIGN KEY FK_6E60B4F741859289');
        $this->addSql('ALTER TABLE organization_role DROP FOREIGN KEY FK_6E60B4F7A76ED395');
        $this->addSql('ALTER TABLE organization_role_division DROP FOREIGN KEY FK_27B456B5727ACA70');
        $this->addSql('DROP TABLE organization_role');
        $this->addSql('DROP TABLE organization_role_division');
        $this->addSql('ALTER TABLE user CHANGE activated enabled TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, division_id INT UNSIGNED NOT NULL, role ENUM(\'Dirigeant\', \'Gérant\', \'Assistant\') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Enum:74988ed5734940d41eae2d75ece2b138)(DC2Type:division_role_enum)\', INDEX IDX_6E60B4F7A76ED395 (user_id), INDEX IDX_6E60B4F741859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE organization_role_division (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_27B456B5727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE organization_role ADD CONSTRAINT FK_6E60B4F741859289 FOREIGN KEY (division_id) REFERENCES organization_role_division (id)');
        $this->addSql('ALTER TABLE organization_role ADD CONSTRAINT FK_6E60B4F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organization_role_division ADD CONSTRAINT FK_27B456B5727ACA70 FOREIGN KEY (parent_id) REFERENCES organization_role_division (id)');
        $this->addSql('ALTER TABLE division DROP FOREIGN KEY FK_10174714727ACA70');
        $this->addSql('ALTER TABLE division_member DROP FOREIGN KEY FK_8AC283FFA76ED395');
        $this->addSql('ALTER TABLE division_member DROP FOREIGN KEY FK_8AC283FF41859289');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE division_member');
        $this->addSql('ALTER TABLE user CHANGE enabled activated TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
