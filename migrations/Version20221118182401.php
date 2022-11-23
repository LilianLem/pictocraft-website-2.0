<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118182401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE survey_survey_user (survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_F3820987B3FE509D (survey_id), INDEX IDX_F3820987A76ED395 (user_id), PRIMARY KEY(survey_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_survey_user ADD CONSTRAINT FK_F3820987B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_survey_user ADD CONSTRAINT FK_F3820987A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE survey_survey_user DROP FOREIGN KEY FK_F3820987B3FE509D');
        $this->addSql('ALTER TABLE survey_survey_user DROP FOREIGN KEY FK_F3820987A76ED395');
        $this->addSql('DROP TABLE survey_survey_user');
    }
}
