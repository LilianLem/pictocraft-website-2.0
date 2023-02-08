<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116143827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order ADD address_commune_postal_data_id INT UNSIGNED DEFAULT NULL, ADD address_country_id INT UNSIGNED DEFAULT NULL, ADD address_line_building_inside VARCHAR(64) DEFAULT NULL, ADD address_line_building_outside VARCHAR(64) DEFAULT NULL, ADD address_line_street VARCHAR(64) DEFAULT NULL, ADD address_line_hamlet VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CAF4D830D8 FOREIGN KEY (address_commune_postal_data_id) REFERENCES geo_france_commune_postal_data (id)');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA81B2B6EE FOREIGN KEY (address_country_id) REFERENCES geo_country (id)');
        $this->addSql('CREATE INDEX IDX_323FC9CAF4D830D8 ON shop_order (address_commune_postal_data_id)');
        $this->addSql('CREATE INDEX IDX_323FC9CA81B2B6EE ON shop_order (address_country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CAF4D830D8');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA81B2B6EE');
        $this->addSql('DROP INDEX IDX_323FC9CAF4D830D8 ON shop_order');
        $this->addSql('DROP INDEX IDX_323FC9CA81B2B6EE ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP address_commune_postal_data_id, DROP address_country_id, DROP address_line_building_inside, DROP address_line_building_outside, DROP address_line_street, DROP address_line_hamlet');
    }
}
