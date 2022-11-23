<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118204444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE survey_constraint (id INT UNSIGNED AUTO_INCREMENT NOT NULL, survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED DEFAULT NULL, role_id INT UNSIGNED DEFAULT NULL, INDEX IDX_7C3084C5B3FE509D (survey_id), INDEX IDX_7C3084C5A76ED395 (user_id), INDEX IDX_7C3084C5D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_constraint ADD CONSTRAINT FK_7C3084C5B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id)');
        $this->addSql('ALTER TABLE survey_constraint ADD CONSTRAINT FK_7C3084C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_constraint ADD CONSTRAINT FK_7C3084C5D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE survey_role_constraint DROP FOREIGN KEY FK_1EAA36EAB3FE509D');
        $this->addSql('ALTER TABLE survey_role_constraint DROP FOREIGN KEY FK_1EAA36EAD60322AC');
        $this->addSql('ALTER TABLE survey_user_constraint DROP FOREIGN KEY FK_36F99A2AA76ED395');
        $this->addSql('ALTER TABLE survey_user_constraint DROP FOREIGN KEY FK_36F99A2AB3FE509D');
        $this->addSql('DROP TABLE survey_role_constraint');
        $this->addSql('DROP TABLE survey_user_constraint');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE survey_role_constraint (survey_id INT UNSIGNED NOT NULL, role_id INT UNSIGNED NOT NULL, INDEX IDX_1EAA36EAB3FE509D (survey_id), INDEX IDX_1EAA36EAD60322AC (role_id), PRIMARY KEY(survey_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE survey_user_constraint (survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_36F99A2AB3FE509D (survey_id), INDEX IDX_36F99A2AA76ED395 (user_id), PRIMARY KEY(survey_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE survey_role_constraint ADD CONSTRAINT FK_1EAA36EAB3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_role_constraint ADD CONSTRAINT FK_1EAA36EAD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_user_constraint ADD CONSTRAINT FK_36F99A2AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_user_constraint ADD CONSTRAINT FK_36F99A2AB3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_constraint DROP FOREIGN KEY FK_7C3084C5B3FE509D');
        $this->addSql('ALTER TABLE survey_constraint DROP FOREIGN KEY FK_7C3084C5A76ED395');
        $this->addSql('ALTER TABLE survey_constraint DROP FOREIGN KEY FK_7C3084C5D60322AC');
        $this->addSql('DROP TABLE survey_constraint');
    }
}
