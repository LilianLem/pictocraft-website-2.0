<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118174253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE survey_answer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, question_id INT UNSIGNED NOT NULL, entry_id INT UNSIGNED NOT NULL, content LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_F2D382491E27F6BF (question_id), INDEX IDX_F2D38249BA364942 (entry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_entry (id INT UNSIGNED AUTO_INCREMENT NOT NULL, survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modifications INT DEFAULT 0 NOT NULL, modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL on update CURRENT_TIMESTAMP, INDEX IDX_27516F48B3FE509D (survey_id), INDEX IDX_27516F48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_question (id INT UNSIGNED AUTO_INCREMENT NOT NULL, survey_id INT UNSIGNED NOT NULL, position INT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, field_type ENUM(\'TextType\', \'TextareaType\', \'EmailType\', \'IntegerType\', \'MoneyType\', \'NumberType\', \'PasswordType\', \'PercentType\', \'SearchType\', \'UrlType\', \'RangeType\', \'TelType\', \'ColorType\', \'ChoiceType\', \'EnumType\', \'EntityType\', \'CountryType\', \'LanguageType\', \'LocaleType\', \'TimezoneType\', \'CurrencyType\', \'DateType\', \'DateIntervalType\', \'DateTimeType\', \'TimeType\', \'BirthdayType\', \'WeekType\', \'CheckboxType\', \'FileType\', \'RadioType\', \'UuidType\', \'UlidType\', \'CollectionType\', \'RepeatedType\', \'HiddenType\', \'ButtonType\', \'ResetType\', \'SubmitType\', \'FormType\') NOT NULL COMMENT \'(DC2Enum:63fe549453e0fd02a3954f49772c534a)(DC2Type:question_field_type_enum)\', answer_list LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', constraints LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_EA000F69B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_survey (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, anonymous TINYINT(1) DEFAULT 0 NOT NULL, editable TINYINT(1) DEFAULT 0 NOT NULL, allowed_modifications INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_user_constraint (survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_36F99A2AB3FE509D (survey_id), INDEX IDX_36F99A2AA76ED395 (user_id), PRIMARY KEY(survey_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_role_constraint (survey_id INT UNSIGNED NOT NULL, role_id INT UNSIGNED NOT NULL, INDEX IDX_1EAA36EAB3FE509D (survey_id), INDEX IDX_1EAA36EAD60322AC (role_id), PRIMARY KEY(survey_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D382491E27F6BF FOREIGN KEY (question_id) REFERENCES survey_question (id)');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249BA364942 FOREIGN KEY (entry_id) REFERENCES survey_entry (id)');
        $this->addSql('ALTER TABLE survey_entry ADD CONSTRAINT FK_27516F48B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id)');
        $this->addSql('ALTER TABLE survey_entry ADD CONSTRAINT FK_27516F48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_question ADD CONSTRAINT FK_EA000F69B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id)');
        $this->addSql('ALTER TABLE survey_user_constraint ADD CONSTRAINT FK_36F99A2AB3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_user_constraint ADD CONSTRAINT FK_36F99A2AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_role_constraint ADD CONSTRAINT FK_1EAA36EAB3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_role_constraint ADD CONSTRAINT FK_1EAA36EAD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D382491E27F6BF');
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D38249BA364942');
        $this->addSql('ALTER TABLE survey_entry DROP FOREIGN KEY FK_27516F48B3FE509D');
        $this->addSql('ALTER TABLE survey_entry DROP FOREIGN KEY FK_27516F48A76ED395');
        $this->addSql('ALTER TABLE survey_question DROP FOREIGN KEY FK_EA000F69B3FE509D');
        $this->addSql('ALTER TABLE survey_user_constraint DROP FOREIGN KEY FK_36F99A2AB3FE509D');
        $this->addSql('ALTER TABLE survey_user_constraint DROP FOREIGN KEY FK_36F99A2AA76ED395');
        $this->addSql('ALTER TABLE survey_role_constraint DROP FOREIGN KEY FK_1EAA36EAB3FE509D');
        $this->addSql('ALTER TABLE survey_role_constraint DROP FOREIGN KEY FK_1EAA36EAD60322AC');
        $this->addSql('DROP TABLE survey_answer');
        $this->addSql('DROP TABLE survey_entry');
        $this->addSql('DROP TABLE survey_question');
        $this->addSql('DROP TABLE survey_survey');
        $this->addSql('DROP TABLE survey_user_constraint');
        $this->addSql('DROP TABLE survey_role_constraint');
    }
}
