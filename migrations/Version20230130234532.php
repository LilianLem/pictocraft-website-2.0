<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130234532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_delivery CHANGE type type ENUM(\'automatic\', \'manual_user\', \'manual_shop\', \'physical\') NOT NULL COMMENT \'(DC2Enum:3487da9af2f5246e116ee872146b02e2)(DC2Type:delivery_type_enum)\'');
        $this->addSql('ALTER TABLE user CHANGE gender gender ENUM(\'M\', \'F\') NOT NULL COMMENT \'(DC2Enum:aaa0324a67cd9d943d7b50e6c3d4ef77)(DC2Type:gender_enum)\'');
        $this->addSql('ALTER TABLE user_stats CHANGE nb_login_attempts nb_login_attempts INT UNSIGNED DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_delivery CHANGE type type ENUM(\'automatic\', \'manual_user\', \'manual_shop\', \'physical\') NOT NULL COMMENT \'(DC2Enum:ba9863e9ae934f382729f73e8fc35ac3)(DC2Type:delivery_type_enum)\'');
        $this->addSql('ALTER TABLE user CHANGE gender gender ENUM(\'M\', \'F\') DEFAULT \'M\' NOT NULL COMMENT \'(DC2Enum:aaa0324a67cd9d943d7b50e6c3d4ef77)(DC2Type:gender_enum)\'');
        $this->addSql('ALTER TABLE user_stats CHANGE nb_login_attempts nb_login_attempts INT UNSIGNED NOT NULL');
    }
}
