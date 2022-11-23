<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123203218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_consents (user_id INT UNSIGNED NOT NULL, statistical_purposes TINYINT(1) NOT NULL, public_username TINYINT(1) NOT NULL, public_departement TINYINT(1) NOT NULL, public_age TINYINT(1) NOT NULL, public_first_login TINYINT(1) NOT NULL, protected_birthday TINYINT(1) NOT NULL, read_and_accepted_rules TINYINT(1) NOT NULL, read_and_accepted_penalty_terms TINYINT(1) NOT NULL, email_contact_purpose TINYINT(1) NOT NULL, phone_contact_purpose TINYINT(1) NOT NULL, email_service_providers_usage TINYINT(1) NOT NULL, username_compliant TINYINT(1) NOT NULL, real_personal_info TINYINT(1) NOT NULL, secret_santa_address_usage TINYINT(1) NOT NULL, main_address_shop_usage TINYINT(1) NOT NULL, main_address_other_usage TINYINT(1) NOT NULL, minecraft_account_usage TINYINT(1) NOT NULL, steam_account_usage TINYINT(1) NOT NULL, discord_account_usage TINYINT(1) NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_consents ADD CONSTRAINT FK_E6572967A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_consents DROP FOREIGN KEY FK_E6572967A76ED395');
        $this->addSql('DROP TABLE user_consents');
    }
}
