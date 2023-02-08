<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208173028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_57698A6A5E237E06 ON role');
        $this->addSql('DROP INDEX UNIQ_57698A6A989D9B62 ON role');
        $this->addSql('ALTER TABLE role ADD displayed_on_profile TINYINT(1) DEFAULT 0 NOT NULL, ADD profile_display_row INT DEFAULT NULL, ADD profile_display_priority INT DEFAULT NULL, DROP slug');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role ADD slug VARCHAR(32) DEFAULT NULL, DROP displayed_on_profile, DROP profile_display_row, DROP profile_display_priority');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A5E237E06 ON role (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A989D9B62 ON role (slug)');
    }
}
