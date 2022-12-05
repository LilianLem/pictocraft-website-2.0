<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205173922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0481D989D9B62 ON badge (slug)');
        $this->addSql('ALTER TABLE badge_category ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C49D626F989D9B62 ON badge_category (slug)');
        $this->addSql('ALTER TABLE division ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX division_slug_unique ON division (slug, parent_id)');
        $this->addSql('ALTER TABLE role ADD slug VARCHAR(32) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A989D9B62 ON role (slug)');
        $this->addSql('ALTER TABLE secret_santa_edition ADD slug VARCHAR(16) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_82F05A57989D9B62 ON secret_santa_edition (slug)');
        $this->addSql('ALTER TABLE secret_santa_participant DROP INDEX UNIQ_5D7CD15DFEB345C3, ADD INDEX IDX_5D7CD15DFEB345C3 (gifting_to_id)');
        $this->addSql('CREATE UNIQUE INDEX secret_santa_gifting_to_unique ON secret_santa_participant (gifting_to_id, edition_id)');
        $this->addSql('ALTER TABLE shop_attribute ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3CD6170989D9B62 ON shop_attribute (slug)');
        $this->addSql('ALTER TABLE shop_attribute_value ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX attribute_value_slug_unique ON shop_attribute_value (attribute_id, slug)');
        $this->addSql('ALTER TABLE shop_category ADD slug VARCHAR(32) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX category_slug_unique ON shop_category (slug, parent_id)');
        $this->addSql('ALTER TABLE shop_product ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0794487989D9B62 ON shop_product (slug)');
        $this->addSql('ALTER TABLE survey_survey ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_85515390989D9B62 ON survey_survey (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_FEF0481D989D9B62 ON badge');
        $this->addSql('ALTER TABLE badge DROP slug');
        $this->addSql('DROP INDEX UNIQ_C49D626F989D9B62 ON badge_category');
        $this->addSql('ALTER TABLE badge_category DROP slug');
        $this->addSql('DROP INDEX division_slug_unique ON division');
        $this->addSql('ALTER TABLE division DROP slug');
        $this->addSql('DROP INDEX UNIQ_57698A6A989D9B62 ON role');
        $this->addSql('ALTER TABLE role DROP slug');
        $this->addSql('DROP INDEX UNIQ_82F05A57989D9B62 ON secret_santa_edition');
        $this->addSql('ALTER TABLE secret_santa_edition DROP slug');
        $this->addSql('ALTER TABLE secret_santa_participant DROP INDEX IDX_5D7CD15DFEB345C3, ADD UNIQUE INDEX UNIQ_5D7CD15DFEB345C3 (gifting_to_id)');
        $this->addSql('DROP INDEX secret_santa_gifting_to_unique ON secret_santa_participant');
        $this->addSql('DROP INDEX UNIQ_E3CD6170989D9B62 ON shop_attribute');
        $this->addSql('ALTER TABLE shop_attribute DROP slug');
        $this->addSql('DROP INDEX attribute_value_slug_unique ON shop_attribute_value');
        $this->addSql('ALTER TABLE shop_attribute_value DROP slug');
        $this->addSql('DROP INDEX category_slug_unique ON shop_category');
        $this->addSql('ALTER TABLE shop_category DROP slug');
        $this->addSql('DROP INDEX UNIQ_D0794487989D9B62 ON shop_product');
        $this->addSql('ALTER TABLE shop_product DROP slug');
        $this->addSql('DROP INDEX UNIQ_85515390989D9B62 ON survey_survey');
        $this->addSql('ALTER TABLE survey_survey DROP slug');
    }
}
