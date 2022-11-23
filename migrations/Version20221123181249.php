<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123181249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(file_get_contents(__DIR__."/data/External/Geo/Country.sql"));
        $this->addSql(file_get_contents(__DIR__."/data/External/Geo/France/Region.sql"));
        $this->addSql(file_get_contents(__DIR__."/data/External/Geo/France/Departement.sql"));
        $this->addSql(file_get_contents(__DIR__."/data/External/Geo/France/Commune.sql"));
        $this->addSql(file_get_contents(__DIR__."/data/External/Geo/France/CommunePostalData.sql"));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM geo_france_commune_postal_data');
        $this->addSql('DELETE FROM geo_france_commune');
        $this->addSql('DELETE FROM geo_france_departement');
        $this->addSql('DELETE FROM geo_france_region');
        $this->addSql('DELETE FROM geo_country');
    }
}
