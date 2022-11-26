<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126184818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE badge (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, name VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_FEF0481D5E237E06 (name), INDEX IDX_FEF0481D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge_category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C49D626F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge_user (user_id INT UNSIGNED NOT NULL, badge_id INT UNSIGNED NOT NULL, obtained_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_299D3A50A76ED395 (user_id), INDEX IDX_299D3A50F7A2C2FC (badge_id), UNIQUE INDEX badge_user_unique (badge_id, user_id), PRIMARY KEY(user_id, badge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT UNSIGNED AUTO_INCREMENT NOT NULL, internal_name VARCHAR(64) NOT NULL, internal_description VARCHAR(255) DEFAULT NULL, route VARCHAR(64) DEFAULT NULL, color ENUM(\'primary\', \'secondary\', \'success\', \'danger\', \'warning\', \'info\', \'light\', \'dark\', \'link\') DEFAULT \'primary\' NOT NULL COMMENT \'(DC2Enum:839238d9608621bc898a7d27db96b78e)(DC2Type:color_enum)\', icon VARCHAR(64) DEFAULT NULL, send_on_website TINYINT(1) NOT NULL, send_by_email TINYINT(1) NOT NULL, send_by_discord_privately TINYINT(1) NOT NULL, send_by_discord_publicly TINYINT(1) NOT NULL, title_for_website VARCHAR(64) DEFAULT NULL, title_for_email VARCHAR(64) DEFAULT NULL, title_for_discord_privately VARCHAR(64) DEFAULT NULL, title_for_discord_publicly VARCHAR(64) DEFAULT NULL, text_for_website LONGTEXT DEFAULT NULL, text_for_email_raw LONGTEXT DEFAULT NULL, text_for_email_html LONGTEXT DEFAULT NULL, text_for_discord_privately LONGTEXT DEFAULT NULL, text_for_discord_publicly LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, notification_id INT UNSIGNED NOT NULL, placeholders_content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', route_parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', generated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', sent_by_email TINYINT(1) NOT NULL, sent_by_discord_privately TINYINT(1) NOT NULL, marked_as_read_on_website_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_35AF9D73A76ED395 (user_id), INDEX IDX_35AF9D73EF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D12469DE2 FOREIGN KEY (category_id) REFERENCES badge_category (id)');
        $this->addSql('ALTER TABLE badge_user ADD CONSTRAINT FK_299D3A50A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE badge_user ADD CONSTRAINT FK_299D3A50F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE UNIQUE INDEX division_unique ON division (name, parent_id)');
        $this->addSql('CREATE UNIQUE INDEX division_user_unique ON division_member (user_id, division_id)');
        $this->addSql('CREATE UNIQUE INDEX postal_data_unique ON geo_france_commune_postal_data (commune_id, postal_code, hamlet)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A5E237E06 ON role (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6AB2A3EFCD ON role (internal_name)');
        $this->addSql('CREATE UNIQUE INDEX role_user_unique ON role_user (role_id, user_id)');
        $this->addSql('ALTER TABLE shop_applied_discount CHANGE fixed_discount fixed_discount INT UNSIGNED DEFAULT NULL, CHANGE percentage_discount percentage_discount INT UNSIGNED DEFAULT NULL, CHANGE amount amount INT UNSIGNED NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX applied_discount_order_unique ON shop_applied_discount (discount_id, order_id)');
        $this->addSql('CREATE UNIQUE INDEX applied_discount_order_item_unique ON shop_applied_discount (discount_id, order_item_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3CD61705E237E06 ON shop_attribute (name)');
        $this->addSql('CREATE UNIQUE INDEX attribute_value_unique ON shop_attribute_value (attribute_id, value)');
        $this->addSql('CREATE UNIQUE INDEX category_unique ON shop_category (name, parent_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A584E9877153098 ON shop_discount (code)');
        $this->addSql('ALTER TABLE shop_discount_constraint CHANGE min_product_price min_product_price INT UNSIGNED DEFAULT NULL, CHANGE max_product_price max_product_price INT UNSIGNED DEFAULT NULL, CHANGE min_order_amount min_order_amount INT UNSIGNED DEFAULT NULL, CHANGE max_order_amount max_order_amount INT UNSIGNED DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX discount_constraint_unique ON shop_discount_constraint (constraint_group_id, product_id, category_id, attribute_value_id, user_id, role_id)');
        $this->addSql('ALTER TABLE shop_discount_constraint_group CHANGE constraints_needed constraints_needed INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE shop_game_key CHANGE added_at added_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE rarity rarity INT UNSIGNED NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_323FC9CAAEA34913 ON shop_order (reference)');
        $this->addSql('CREATE UNIQUE INDEX order_item_unique ON shop_order_item (order_id, item_id)');
        $this->addSql('CREATE UNIQUE INDEX product_category_unique ON shop_product_category (product_id, category_id)');
        $this->addSql('CREATE UNIQUE INDEX answer_unique ON survey_answer (question_id, entry_id)');
        $this->addSql('CREATE UNIQUE INDEX survey_user_constraint_unique ON survey_constraint (survey_id, user_id)');
        $this->addSql('CREATE UNIQUE INDEX survey_role_constraint_unique ON survey_constraint (survey_id, role_id)');
        $this->addSql('ALTER TABLE survey_survey ADD number_of_entries_allowed INT UNSIGNED NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491D7DE02C ON user (voting_code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4054973C6C2 ON user_profile (minecraft_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB405F3FD4ECA ON user_profile (steam_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB40543349DE ON user_profile (discord_id)');
        $this->addSql('ALTER TABLE user_stats CHANGE nb_login_attempts nb_login_attempts INT UNSIGNED NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX user_steam_game_unique ON user_steam_game (game_id, user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D12469DE2');
        $this->addSql('ALTER TABLE badge_user DROP FOREIGN KEY FK_299D3A50A76ED395');
        $this->addSql('ALTER TABLE badge_user DROP FOREIGN KEY FK_299D3A50F7A2C2FC');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE badge_category');
        $this->addSql('DROP TABLE badge_user');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP INDEX division_unique ON division');
        $this->addSql('DROP INDEX division_user_unique ON division_member');
        $this->addSql('DROP INDEX postal_data_unique ON geo_france_commune_postal_data');
        $this->addSql('DROP INDEX UNIQ_57698A6A5E237E06 ON role');
        $this->addSql('DROP INDEX UNIQ_57698A6AB2A3EFCD ON role');
        $this->addSql('DROP INDEX role_user_unique ON role_user');
        $this->addSql('DROP INDEX applied_discount_order_unique ON shop_applied_discount');
        $this->addSql('DROP INDEX applied_discount_order_item_unique ON shop_applied_discount');
        $this->addSql('ALTER TABLE shop_applied_discount CHANGE fixed_discount fixed_discount INT DEFAULT NULL, CHANGE percentage_discount percentage_discount INT DEFAULT NULL, CHANGE amount amount INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_E3CD61705E237E06 ON shop_attribute');
        $this->addSql('DROP INDEX attribute_value_unique ON shop_attribute_value');
        $this->addSql('DROP INDEX category_unique ON shop_category');
        $this->addSql('DROP INDEX UNIQ_3A584E9877153098 ON shop_discount');
        $this->addSql('DROP INDEX discount_constraint_unique ON shop_discount_constraint');
        $this->addSql('ALTER TABLE shop_discount_constraint CHANGE min_product_price min_product_price INT DEFAULT NULL, CHANGE max_product_price max_product_price INT DEFAULT NULL, CHANGE min_order_amount min_order_amount INT DEFAULT NULL, CHANGE max_order_amount max_order_amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_discount_constraint_group CHANGE constraints_needed constraints_needed INT NOT NULL');
        $this->addSql('ALTER TABLE shop_game_key CHANGE added_at added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE rarity rarity INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_323FC9CAAEA34913 ON shop_order');
        $this->addSql('DROP INDEX order_item_unique ON shop_order_item');
        $this->addSql('DROP INDEX product_category_unique ON shop_product_category');
        $this->addSql('DROP INDEX answer_unique ON survey_answer');
        $this->addSql('DROP INDEX survey_user_constraint_unique ON survey_constraint');
        $this->addSql('DROP INDEX survey_role_constraint_unique ON survey_constraint');
        $this->addSql('ALTER TABLE survey_survey DROP number_of_entries_allowed');
        $this->addSql('DROP INDEX UNIQ_8D93D6491D7DE02C ON user');
        $this->addSql('DROP INDEX UNIQ_D95AB4054973C6C2 ON user_profile');
        $this->addSql('DROP INDEX UNIQ_D95AB405F3FD4ECA ON user_profile');
        $this->addSql('DROP INDEX UNIQ_D95AB40543349DE ON user_profile');
        $this->addSql('ALTER TABLE user_stats CHANGE nb_login_attempts nb_login_attempts INT NOT NULL');
        $this->addSql('DROP INDEX user_steam_game_unique ON user_steam_game');
    }
}
