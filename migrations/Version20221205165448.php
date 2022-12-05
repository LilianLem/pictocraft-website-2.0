<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205165448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE secret_santa_edition (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_82F05A575E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secret_santa_participant (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, edition_id INT UNSIGNED NOT NULL, gifting_to_id INT UNSIGNED DEFAULT NULL, registered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', requested_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address_request_answer TINYINT(1) DEFAULT NULL, address_request_answer_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', saw_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gift_message VARCHAR(150) DEFAULT NULL, gift_message_last_updated_at DATETIME DEFAULT NULL, sent_pickup_location TINYINT(1) DEFAULT NULL, informed_delivery_at_pickup_location TINYINT(1) DEFAULT NULL, informed_home_delivery_shipment TINYINT(1) DEFAULT NULL, INDEX IDX_5D7CD15DA76ED395 (user_id), INDEX IDX_5D7CD15D74281A5E (edition_id), UNIQUE INDEX UNIQ_5D7CD15DFEB345C3 (gifting_to_id), UNIQUE INDEX secret_santa_participant_unique (user_id, edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_user_anonymous (id INT UNSIGNED AUTO_INCREMENT NOT NULL, survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, times_answered INT UNSIGNED NOT NULL, INDEX IDX_71DC01E7B3FE509D (survey_id), INDEX IDX_71DC01E7A76ED395 (user_id), UNIQUE INDEX survey_user_anonymous_unique (survey_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE secret_santa_participant ADD CONSTRAINT FK_5D7CD15DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE secret_santa_participant ADD CONSTRAINT FK_5D7CD15D74281A5E FOREIGN KEY (edition_id) REFERENCES secret_santa_edition (id)');
        $this->addSql('ALTER TABLE secret_santa_participant ADD CONSTRAINT FK_5D7CD15DFEB345C3 FOREIGN KEY (gifting_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_user_anonymous ADD CONSTRAINT FK_71DC01E7B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id)');
        $this->addSql('ALTER TABLE survey_user_anonymous ADD CONSTRAINT FK_71DC01E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE secret_santa DROP FOREIGN KEY FK_9EB41A9BA76ED395');
        $this->addSql('ALTER TABLE secret_santa DROP FOREIGN KEY FK_9EB41A9BFEB345C3');
        $this->addSql('ALTER TABLE survey_survey_user DROP FOREIGN KEY FK_F3820987B3FE509D');
        $this->addSql('ALTER TABLE survey_survey_user DROP FOREIGN KEY FK_F3820987A76ED395');
        $this->addSql('DROP TABLE secret_santa');
        $this->addSql('DROP TABLE survey_survey_user');
        $this->addSql('ALTER TABLE user ADD access_granted_by_id INT UNSIGNED DEFAULT NULL, ADD access_granted_type ENUM(\'staff\', \'member\', \'legacy\') DEFAULT NULL COMMENT \'(DC2Enum:85f9ff7df9ee1b24dfce39d4f8422ffd)(DC2Type:access_grant_enum)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A95FAD3B FOREIGN KEY (access_granted_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A95FAD3B ON user (access_granted_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE secret_santa (user_id INT UNSIGNED NOT NULL, gifting_to_id INT UNSIGNED DEFAULT NULL, registered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', requested_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address_request_answer TINYINT(1) DEFAULT NULL, address_request_answer_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', saw_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gift_message VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, gift_message_last_updated_at DATETIME DEFAULT NULL, sent_pickup_location TINYINT(1) DEFAULT NULL, informed_delivery TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_9EB41A9BFEB345C3 (gifting_to_id), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE survey_survey_user (survey_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_F3820987B3FE509D (survey_id), INDEX IDX_F3820987A76ED395 (user_id), PRIMARY KEY(survey_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE secret_santa ADD CONSTRAINT FK_9EB41A9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE secret_santa ADD CONSTRAINT FK_9EB41A9BFEB345C3 FOREIGN KEY (gifting_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_survey_user ADD CONSTRAINT FK_F3820987B3FE509D FOREIGN KEY (survey_id) REFERENCES survey_survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_survey_user ADD CONSTRAINT FK_F3820987A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE secret_santa_participant DROP FOREIGN KEY FK_5D7CD15DA76ED395');
        $this->addSql('ALTER TABLE secret_santa_participant DROP FOREIGN KEY FK_5D7CD15D74281A5E');
        $this->addSql('ALTER TABLE secret_santa_participant DROP FOREIGN KEY FK_5D7CD15DFEB345C3');
        $this->addSql('ALTER TABLE survey_user_anonymous DROP FOREIGN KEY FK_71DC01E7B3FE509D');
        $this->addSql('ALTER TABLE survey_user_anonymous DROP FOREIGN KEY FK_71DC01E7A76ED395');
        $this->addSql('DROP TABLE secret_santa_edition');
        $this->addSql('DROP TABLE secret_santa_participant');
        $this->addSql('DROP TABLE survey_user_anonymous');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A95FAD3B');
        $this->addSql('DROP INDEX IDX_8D93D649A95FAD3B ON user');
        $this->addSql('ALTER TABLE user DROP access_granted_by_id, DROP access_granted_type');
    }
}
