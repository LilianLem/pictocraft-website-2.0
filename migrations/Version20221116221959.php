<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221116221959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE secret_santa (user_id INT UNSIGNED NOT NULL, gifting_to_id INT UNSIGNED DEFAULT NULL, registered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', requested_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address_request_answer TINYINT(1) DEFAULT NULL, address_request_answer_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', saw_address_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gift_message VARCHAR(150) DEFAULT NULL, gift_message_last_updated_at DATETIME DEFAULT NULL, sent_pickup_location TINYINT(1) DEFAULT NULL, informed_delivery TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_9EB41A9BFEB345C3 (gifting_to_id), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_attribute (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, hidden TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_attribute_value (id INT UNSIGNED AUTO_INCREMENT NOT NULL, attribute_id INT UNSIGNED NOT NULL, value VARCHAR(64) NOT NULL, hidden TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_17BCBFB6B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(32) NOT NULL, hidden TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_DDF4E357727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_delivery (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, type ENUM(\'automatic\', \'manual_user\', \'manual_shop\') NOT NULL COMMENT \'(DC2Enum:ba9863e9ae934f382729f73e8fc35ac3)(DC2Type:delivery_type_enum)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_game_key (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_item_id INT UNSIGNED DEFAULT NULL, redeemed_code_id INT UNSIGNED DEFAULT NULL, code VARCHAR(29) NOT NULL, game_name VARCHAR(64) NOT NULL, key_type ENUM(\'key\', \'gift\') NOT NULL COMMENT \'(DC2Enum:6d5fda7e097be8441726f11c13bda024)(DC2Type:game_key_type_enum)\', genres VARCHAR(32) DEFAULT NULL, steam_id INT DEFAULT NULL, shop_source VARCHAR(32) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_to_draw TINYINT(1) DEFAULT 1 NOT NULL, destination ENUM(\'random\', \'yogscast\', \'other\') NOT NULL COMMENT \'(DC2Enum:97c4156e0c6bdba60b45a873de86ecd8)(DC2Type:game_key_destination_enum)\', UNIQUE INDEX UNIQ_4A7242FA77153098 (code), INDEX IDX_4A7242FAE415FB15 (order_item_id), UNIQUE INDEX UNIQ_4A7242FA68295080 (redeemed_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_order (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, payment_method_id INT UNSIGNED NOT NULL, status_id INT UNSIGNED NOT NULL, reference VARCHAR(9) NOT NULL, price_ht INT UNSIGNED DEFAULT 0 NOT NULL, price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME on update CURRENT_TIMESTAMP, paypal_token VARCHAR(20) DEFAULT NULL, INDEX IDX_323FC9CAA76ED395 (user_id), INDEX IDX_323FC9CA5AA1164F (payment_method_id), INDEX IDX_323FC9CA6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_order_item (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED NOT NULL, item_id INT UNSIGNED NOT NULL, delivery_id INT UNSIGNED NOT NULL, status_id INT UNSIGNED NOT NULL, gifted_to_id INT UNSIGNED DEFAULT NULL, base_price_ht INT UNSIGNED DEFAULT 0 NOT NULL, base_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, price_ht INT UNSIGNED DEFAULT 0 NOT NULL, price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, comment VARCHAR(255) DEFAULT NULL, updated_on DATETIME on update CURRENT_TIMESTAMP, amount INT UNSIGNED DEFAULT 1 NOT NULL, INDEX IDX_2899F22F8D9F6D38 (order_id), INDEX IDX_2899F22F126F525E (item_id), INDEX IDX_2899F22F12136921 (delivery_id), INDEX IDX_2899F22F6BF700BD (status_id), INDEX IDX_2899F22F910DCCC8 (gifted_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_payment_method (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, type ENUM(\'automatic\', \'manual\') NOT NULL COMMENT \'(DC2Enum:458d5f04b84f55f78a894b9c3b7c3f07)(DC2Type:payment_method_type_enum)\', enabled TINYINT(1) DEFAULT 0 NOT NULL, selectable TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product (id INT UNSIGNED AUTO_INCREMENT NOT NULL, delivery_id INT UNSIGNED NOT NULL, name VARCHAR(64) NOT NULL, reference VARCHAR(4) NOT NULL, description LONGTEXT DEFAULT NULL, subtitle VARCHAR(48) DEFAULT NULL, image VARCHAR(64) DEFAULT NULL, buyable TINYINT(1) DEFAULT 0 NOT NULL, hidden TINYINT(1) DEFAULT 0 NOT NULL, enabled TINYINT(1) DEFAULT 0 NOT NULL, amount INT DEFAULT 0 NOT NULL, base_price_ht INT UNSIGNED DEFAULT 0 NOT NULL, base_price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, price_ht INT UNSIGNED DEFAULT 0 NOT NULL, price_ttc INT UNSIGNED DEFAULT 0 NOT NULL, public_discount_text VARCHAR(64) DEFAULT NULL, INDEX IDX_D079448712136921 (delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_value (product_id INT UNSIGNED NOT NULL, attribute_value_id INT UNSIGNED NOT NULL, INDEX IDX_CCC4BE1F4584665A (product_id), INDEX IDX_CCC4BE1F65A22152 (attribute_value_id), PRIMARY KEY(product_id, attribute_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product_category (product_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, main TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_ECA174E74584665A (product_id), INDEX IDX_ECA174E712469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_redemption_code (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, access_id INT UNSIGNED DEFAULT NULL, code VARCHAR(10) NOT NULL, comment VARCHAR(255) DEFAULT NULL, key_type ENUM(\'key\', \'gift\') NOT NULL COMMENT \'(DC2Enum:6d5fda7e097be8441726f11c13bda024)(DC2Type:game_key_type_enum)\', available TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_4ACFB42CA76ED395 (user_id), INDEX IDX_4ACFB42C4FEA67CF (access_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_redemption_code_access (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, order_compatible TINYINT(1) NOT NULL, order_item_compatible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE steam_game (id INT UNSIGNED NOT NULL, name VARCHAR(128) NOT NULL, img_logo_url VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(32) NOT NULL, first_name VARCHAR(32) DEFAULT NULL, last_name VARCHAR(32) DEFAULT NULL, gender ENUM(\'M\', \'F\') DEFAULT \'M\' NOT NULL COMMENT \'(DC2Enum:aaa0324a67cd9d943d7b50e6c3d4ef77)(DC2Type:gender_enum)\', birthday DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', first_login VARCHAR(64) NOT NULL, warnings INT DEFAULT 0 NOT NULL, organization_roles VARCHAR(255) DEFAULT NULL, voting_code INT NOT NULL, shop_balance INT UNSIGNED DEFAULT 0 NOT NULL, secret_santa_eligible TINYINT(1) DEFAULT 0 NOT NULL, christmas_gift_eligible TINYINT(1) DEFAULT 0 NOT NULL, activated TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (user_id INT UNSIGNED NOT NULL, minecraft_uuid VARCHAR(32) DEFAULT NULL, steam_id VARCHAR(17) DEFAULT NULL, discord_id VARCHAR(32) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, games VARCHAR(255) DEFAULT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_settings (user_id INT UNSIGNED NOT NULL, avoid_duplicate_games TINYINT(1) NOT NULL, city_code INT DEFAULT NULL, street VARCHAR(64) DEFAULT NULL, street2 VARCHAR(64) DEFAULT NULL, zipcode VARCHAR(16) DEFAULT NULL, ss_street VARCHAR(64) DEFAULT NULL, ss_street2 VARCHAR(64) DEFAULT NULL, ss_zipcode VARCHAR(16) DEFAULT NULL, ss_city VARCHAR(64) DEFAULT NULL, ss_country VARCHAR(2) DEFAULT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_stats (user_id INT UNSIGNED NOT NULL, last_steam_check_at DATETIME DEFAULT NULL, last_login_at DATETIME DEFAULT NULL, last_redeemed_at DATETIME DEFAULT NULL, last_login_attempt_at DATETIME DEFAULT NULL, nb_login_attempts INT NOT NULL, gifted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_steam_game (game_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4D504158E48FD905 (game_id), INDEX IDX_4D504158A76ED395 (user_id), PRIMARY KEY(game_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE secret_santa ADD CONSTRAINT FK_9EB41A9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE secret_santa ADD CONSTRAINT FK_9EB41A9BFEB345C3 FOREIGN KEY (gifting_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_attribute_value ADD CONSTRAINT FK_17BCBFB6B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_attribute (id)');
        $this->addSql('ALTER TABLE shop_category ADD CONSTRAINT FK_DDF4E357727ACA70 FOREIGN KEY (parent_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE shop_game_key ADD CONSTRAINT FK_4A7242FAE415FB15 FOREIGN KEY (order_item_id) REFERENCES shop_order_item (id)');
        $this->addSql('ALTER TABLE shop_game_key ADD CONSTRAINT FK_4A7242FA68295080 FOREIGN KEY (redeemed_code_id) REFERENCES shop_redemption_code (id)');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA5AA1164F FOREIGN KEY (payment_method_id) REFERENCES shop_payment_method (id)');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA6BF700BD FOREIGN KEY (status_id) REFERENCES shop_status (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F8D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F126F525E FOREIGN KEY (item_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F12136921 FOREIGN KEY (delivery_id) REFERENCES shop_delivery (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F6BF700BD FOREIGN KEY (status_id) REFERENCES shop_status (id)');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F910DCCC8 FOREIGN KEY (gifted_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448712136921 FOREIGN KEY (delivery_id) REFERENCES shop_delivery (id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F65A22152 FOREIGN KEY (attribute_value_id) REFERENCES shop_attribute_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_category ADD CONSTRAINT FK_ECA174E74584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE shop_product_category ADD CONSTRAINT FK_ECA174E712469DE2 FOREIGN KEY (category_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE shop_redemption_code ADD CONSTRAINT FK_4ACFB42CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_redemption_code ADD CONSTRAINT FK_4ACFB42C4FEA67CF FOREIGN KEY (access_id) REFERENCES shop_redemption_code_access (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_stats ADD CONSTRAINT FK_B5859CF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_steam_game ADD CONSTRAINT FK_4D504158E48FD905 FOREIGN KEY (game_id) REFERENCES steam_game (id)');
        $this->addSql('ALTER TABLE user_steam_game ADD CONSTRAINT FK_4D504158A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secret_santa DROP FOREIGN KEY FK_9EB41A9BA76ED395');
        $this->addSql('ALTER TABLE secret_santa DROP FOREIGN KEY FK_9EB41A9BFEB345C3');
        $this->addSql('ALTER TABLE shop_attribute_value DROP FOREIGN KEY FK_17BCBFB6B6E62EFA');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E357727ACA70');
        $this->addSql('ALTER TABLE shop_game_key DROP FOREIGN KEY FK_4A7242FAE415FB15');
        $this->addSql('ALTER TABLE shop_game_key DROP FOREIGN KEY FK_4A7242FA68295080');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CAA76ED395');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA5AA1164F');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA6BF700BD');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F8D9F6D38');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F126F525E');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F12136921');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F6BF700BD');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F910DCCC8');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448712136921');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F4584665A');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F65A22152');
        $this->addSql('ALTER TABLE shop_product_category DROP FOREIGN KEY FK_ECA174E74584665A');
        $this->addSql('ALTER TABLE shop_product_category DROP FOREIGN KEY FK_ECA174E712469DE2');
        $this->addSql('ALTER TABLE shop_redemption_code DROP FOREIGN KEY FK_4ACFB42CA76ED395');
        $this->addSql('ALTER TABLE shop_redemption_code DROP FOREIGN KEY FK_4ACFB42C4FEA67CF');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('ALTER TABLE user_settings DROP FOREIGN KEY FK_5C844C5A76ED395');
        $this->addSql('ALTER TABLE user_stats DROP FOREIGN KEY FK_B5859CF2A76ED395');
        $this->addSql('ALTER TABLE user_steam_game DROP FOREIGN KEY FK_4D504158E48FD905');
        $this->addSql('ALTER TABLE user_steam_game DROP FOREIGN KEY FK_4D504158A76ED395');
        $this->addSql('DROP TABLE secret_santa');
        $this->addSql('DROP TABLE shop_attribute');
        $this->addSql('DROP TABLE shop_attribute_value');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE shop_delivery');
        $this->addSql('DROP TABLE shop_game_key');
        $this->addSql('DROP TABLE shop_order');
        $this->addSql('DROP TABLE shop_order_item');
        $this->addSql('DROP TABLE shop_payment_method');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE product_attribute_value');
        $this->addSql('DROP TABLE shop_product_category');
        $this->addSql('DROP TABLE shop_redemption_code');
        $this->addSql('DROP TABLE shop_redemption_code_access');
        $this->addSql('DROP TABLE shop_status');
        $this->addSql('DROP TABLE steam_game');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE user_settings');
        $this->addSql('DROP TABLE user_stats');
        $this->addSql('DROP TABLE user_steam_game');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
