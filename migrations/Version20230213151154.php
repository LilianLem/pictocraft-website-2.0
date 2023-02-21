<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213151154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Manually changed to optimize it (changes badly detected by Symfony because of file names partially swapped)
        $this->addSql('RENAME TABLE notification TO notification_type');
        $this->addSql('RENAME TABLE notification_user TO notification');
        $this->addSql('ALTER TABLE notification RENAME COLUMN notification_id TO type_id');
        $this->addSql('ALTER TABLE notification RENAME INDEX IDX_35AF9D73A76ED395 TO IDX_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification RENAME INDEX IDX_35AF9D73EF1A9D84 TO IDX_BF5476CAC54C8C93');
    }

    public function down(Schema $schema): void
    {
        // Manually changed to optimize it (changes badly detected by Symfony because of file names partially swapped)
        $this->addSql('ALTER TABLE notification RENAME INDEX IDX_BF5476CAC54C8C93 TO IDX_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE notification RENAME INDEX IDX_BF5476CAA76ED395 TO IDX_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE notification RENAME COLUMN type_id TO notification_id');
        $this->addSql('RENAME TABLE notification TO notification_user');
        $this->addSql('RENAME TABLE notification_type TO notification');
    }
}
