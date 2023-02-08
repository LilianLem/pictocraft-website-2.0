<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201224926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE division ADD internal_role_prefix VARCHAR(17) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10174714DF827BA ON division (internal_role_prefix)');
        $this->addSql('ALTER TABLE role ADD discord_id VARCHAR(32) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A43349DE ON role (discord_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_10174714DF827BA ON division');
        $this->addSql('ALTER TABLE division DROP internal_role_prefix');
        $this->addSql('DROP INDEX UNIQ_57698A6A43349DE ON role');
        $this->addSql('ALTER TABLE role DROP discord_id');
    }
}
