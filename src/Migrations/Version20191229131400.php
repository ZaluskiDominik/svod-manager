<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191229131400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // Customer entity
        $this->addSql("ALTER TABLE customer ADD CONSTRAINT cstr_c_first_name CHECK(LENGTH(first_name) > 0)");
        $this->addSql("ALTER TABLE customer ADD CONSTRAINT cstr_c_surname CHECK(LENGTH(surname) > 0)");
        $this->addSql("ALTER TABLE customer ADD CONSTRAINT cstr_c_email CHECK(LENGTH(email) > 0)");
        $this->addSql("ALTER TABLE customer ADD CONSTRAINT cstr_c_password_hash CHECK(LENGTH(password_hash) > 0)");
        $this->addSql("ALTER TABLE customer ADD CONSTRAINT cstr_c_account_balance CHECK(account_balance >= 0)");

        // Publisher entity
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_first_name CHECK(LENGTH(first_name) > 0)");
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_surname CHECK(LENGTH(surname) > 0)");
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_email CHECK(LENGTH(email) > 0)");
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_password_hash CHECK(LENGTH(password_hash) > 0)");
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_account_balance CHECK(account_balance >= 0)");
        $this->addSql("ALTER TABLE publisher ADD CONSTRAINT cstr_p_company CHECK(LENGTH(company) > 0)");

        // PurchasedSubscription entity
        $this->addSql("ALTER TABLE purchased_subscription ADD CONSTRAINT cstr_purchased_price
            CHECK(price >= 5 AND price < 1000)");
        $this->addSql("ALTER TABLE purchased_subscription ADD CONSTRAINT cstr_purchased_start_end
            CHECK(start_date < active_to)");

        // Subscription entity
        $this->addSql("ALTER TABLE subscription ADD CONSTRAINT cstr_sub_name CHECK(LENGTH(name) > 0)");
        $this->addSql("ALTER TABLE subscription ADD CONSTRAINT cstr_sub_price CHECK(price >= 5 AND price < 1000)");

        // Video entity
        $this->addSql("ALTER TABLE video ADD CONSTRAINT cstr_video_title CHECK(LENGTH(title) > 0)");
        $this->addSql("ALTER TABLE video ADD CONSTRAINT cstr_embed_code CHECK(LENGTH(embed_code) > 0)");
        $this->addSql("ALTER TABLE video ADD CONSTRAINT cstr_description CHECK(LENGTH(description) > 0)");
        $this->addSql("ALTER TABLE video ADD CONSTRAINT cstr_poster_url CHECK(LENGTH(poster_url) > 0)");
    }

    public function down(Schema $schema) : void
    {
        // Customer entity
        $this->addSql("ALTER TABLE customer DROP constraint cstr_c_first_name");
        $this->addSql("ALTER TABLE customer DROP constraint cstr_c_surname");
        $this->addSql("ALTER TABLE customer DROP constraint cstr_c_email");
        $this->addSql("ALTER TABLE customer DROP constraint cstr_c_password_hash");
        $this->addSql("ALTER TABLE customer DROP constraint cstr_c_account_balance");

        // Publisher entity
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_first_name");
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_surname");
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_email");
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_password_hash");
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_account_balance");
        $this->addSql("ALTER TABLE publisher DROP constraint cstr_p_company");

        // PurchasedSubscription entity
        $this->addSql("ALTER TABLE purchased_subscription DROP CONSTRAINT cstr_purchased_price");
        $this->addSql("ALTER TABLE purchased_subscription DROP CONSTRAINT cstr_purchased_start_end");

        // Subscription entity
        $this->addSql("ALTER TABLE subscription DROP CONSTRAINT cstr_sub_name");
        $this->addSql("ALTER TABLE subscription DROP CONSTRAINT cstr_sub_price");

        // Video entity
        $this->addSql("ALTER TABLE video DROP CONSTRAINT cstr_video_title");
        $this->addSql("ALTER TABLE video DROP CONSTRAINT cstr_embed_code");
        $this->addSql("ALTER TABLE video DROP CONSTRAINT cstr_description");
        $this->addSql("ALTER TABLE video DROP CONSTRAINT cstr_poster_url");
    }
}
