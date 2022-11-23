<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123141823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE geo_country (id INT UNSIGNED AUTO_INCREMENT NOT NULL, iso_code_alpha2 VARCHAR(2) NOT NULL, iso_code_alpha3 VARCHAR(3) NOT NULL, name VARCHAR(64) NOT NULL, name_for_sorting VARCHAR(64) NOT NULL, article VARCHAR(3) DEFAULT NULL, UNIQUE INDEX UNIQ_E4654464B19E32DF (iso_code_alpha2), UNIQUE INDEX UNIQ_E4654464C6990249 (iso_code_alpha3), UNIQUE INDEX UNIQ_E46544645E237E06 (name), UNIQUE INDEX UNIQ_E46544648EB445AF (name_for_sorting), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_france_commune (id INT UNSIGNED NOT NULL, departement_id INT UNSIGNED NOT NULL, insee_code VARCHAR(5) NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_4B417F8215A3C1BC (insee_code), INDEX IDX_4B417F82CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_france_commune_postal_data (id INT UNSIGNED AUTO_INCREMENT NOT NULL, commune_id INT UNSIGNED NOT NULL, postal_code VARCHAR(5) NOT NULL, hamlet VARCHAR(64) DEFAULT NULL, latitude NUMERIC(11, 9) DEFAULT NULL, longitude NUMERIC(12, 9) DEFAULT NULL, INDEX IDX_A25A347D131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_france_departement (id INT UNSIGNED NOT NULL, region_id INT UNSIGNED NOT NULL, insee_code VARCHAR(3) NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_494097D415A3C1BC (insee_code), UNIQUE INDEX UNIQ_494097D45E237E06 (name), INDEX IDX_494097D498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_france_region (id INT UNSIGNED NOT NULL, name VARCHAR(64) NOT NULL, iso_code VARCHAR(3) NOT NULL, UNIQUE INDEX UNIQ_1062CE9A5E237E06 (name), UNIQUE INDEX UNIQ_1062CE9A62B6A45E (iso_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE geo_france_commune ADD CONSTRAINT FK_4B417F82CCF9E01E FOREIGN KEY (departement_id) REFERENCES geo_france_departement (id)');
        $this->addSql('ALTER TABLE geo_france_commune_postal_data ADD CONSTRAINT FK_A25A347D131A4F72 FOREIGN KEY (commune_id) REFERENCES geo_france_commune (id)');
        $this->addSql('ALTER TABLE geo_france_departement ADD CONSTRAINT FK_494097D498260155 FOREIGN KEY (region_id) REFERENCES geo_france_region (id)');
        $this->addSql('ALTER TABLE user_settings ADD departement_id INT UNSIGNED DEFAULT NULL, ADD country_id INT UNSIGNED DEFAULT NULL, ADD address_commune_postal_data_id INT UNSIGNED DEFAULT NULL, ADD address_country_id INT UNSIGNED DEFAULT NULL, ADD ss_address_commune_postal_data_id INT UNSIGNED DEFAULT NULL, ADD ss_address_country_id INT UNSIGNED DEFAULT NULL, ADD address_line_building_inside VARCHAR(64) DEFAULT NULL, ADD address_line_building_outside VARCHAR(64) DEFAULT NULL, ADD address_line_street VARCHAR(64) DEFAULT NULL, ADD address_line_hamlet VARCHAR(64) DEFAULT NULL, ADD ss_address_line_building_inside VARCHAR(64) DEFAULT NULL, ADD ss_address_line_building_outside VARCHAR(64) DEFAULT NULL, ADD ss_address_line_street VARCHAR(64) DEFAULT NULL, ADD ss_address_line_hamlet VARCHAR(64) DEFAULT NULL, ADD phone_number VARCHAR(16) DEFAULT NULL, DROP city_code, DROP street, DROP street2, DROP zipcode, DROP ss_street, DROP ss_street2, DROP ss_zipcode, DROP ss_city, DROP ss_country, DROP city');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5CCF9E01E FOREIGN KEY (departement_id) REFERENCES geo_france_departement (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5F92F3E70 FOREIGN KEY (country_id) REFERENCES geo_country (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5F4D830D8 FOREIGN KEY (address_commune_postal_data_id) REFERENCES geo_france_commune_postal_data (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C581B2B6EE FOREIGN KEY (address_country_id) REFERENCES geo_country (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5902AEC65 FOREIGN KEY (ss_address_commune_postal_data_id) REFERENCES geo_france_commune_postal_data (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C589C01310 FOREIGN KEY (ss_address_country_id) REFERENCES geo_country (id)');
        $this->addSql('CREATE INDEX IDX_5C844C5CCF9E01E ON user_settings (departement_id)');
        $this->addSql('CREATE INDEX IDX_5C844C5F92F3E70 ON user_settings (country_id)');
        $this->addSql('CREATE INDEX IDX_5C844C5F4D830D8 ON user_settings (address_commune_postal_data_id)');
        $this->addSql('CREATE INDEX IDX_5C844C581B2B6EE ON user_settings (address_country_id)');
        $this->addSql('CREATE INDEX IDX_5C844C5902AEC65 ON user_settings (ss_address_commune_postal_data_id)');
        $this->addSql('CREATE INDEX IDX_5C844C589C01310 ON user_settings (ss_address_country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C5F92F3E70');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C581B2B6EE');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C589C01310');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C5F4D830D8');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C5902AEC65');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C5CCF9E01E');
        $this->addSql('ALTER TABLE geo_france_commune DROP FOREIGN KEY FK_4B417F82CCF9E01E');
        $this->addSql('ALTER TABLE geo_france_commune_postal_data DROP FOREIGN KEY FK_A25A347D131A4F72');
        $this->addSql('ALTER TABLE geo_france_departement DROP FOREIGN KEY FK_494097D498260155');
        $this->addSql('DROP TABLE geo_country');
        $this->addSql('DROP TABLE geo_france_commune');
        $this->addSql('DROP TABLE geo_france_commune_postal_data');
        $this->addSql('DROP TABLE geo_france_departement');
        $this->addSql('DROP TABLE geo_france_region');
        $this->addSql('DROP INDEX IDX_5C844C5CCF9E01E ON user_settings');
        $this->addSql('DROP INDEX IDX_5C844C5F92F3E70 ON user_settings');
        $this->addSql('DROP INDEX IDX_5C844C5F4D830D8 ON user_settings');
        $this->addSql('DROP INDEX IDX_5C844C581B2B6EE ON user_settings');
        $this->addSql('DROP INDEX IDX_5C844C5902AEC65 ON user_settings');
        $this->addSql('DROP INDEX IDX_5C844C589C01310 ON user_settings');
        $this->addSql('ALTER TABLE user_settings ADD city_code INT DEFAULT NULL, ADD street VARCHAR(64) DEFAULT NULL, ADD street2 VARCHAR(64) DEFAULT NULL, ADD ss_street VARCHAR(64) DEFAULT NULL, ADD ss_street2 VARCHAR(64) DEFAULT NULL, ADD ss_zipcode VARCHAR(16) DEFAULT NULL, ADD ss_city VARCHAR(64) DEFAULT NULL, ADD ss_country VARCHAR(2) DEFAULT NULL, ADD city VARCHAR(64) DEFAULT NULL, DROP departement_id, DROP country_id, DROP address_commune_postal_data_id, DROP address_country_id, DROP ss_address_commune_postal_data_id, DROP ss_address_country_id, DROP address_line_building_inside, DROP address_line_building_outside, DROP address_line_street, DROP address_line_hamlet, DROP ss_address_line_building_inside, DROP ss_address_line_building_outside, DROP ss_address_line_street, DROP ss_address_line_hamlet, CHANGE phone_number zipcode VARCHAR(16) DEFAULT NULL');
    }
}
