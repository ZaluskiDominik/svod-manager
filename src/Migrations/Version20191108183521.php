<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108183521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(20) NOT NULL, surname VARCHAR(20) NOT NULL, email VARCHAR(100) NOT NULL, password_hash VARCHAR(60) NOT NULL, account_balance DOUBLE PRECISION NOT NULL, company VARCHAR(100) NOT NULL, company_website VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_entity (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, name VARCHAR(40) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C7DE5CEF40C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_player_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, template_embed_code LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription_entity ADD CONSTRAINT FK_C7DE5CEF40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('DROP TABLE publisher_entity');
        $this->addSql('ALTER TABLE customer CHANGE balance account_balance DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subscription_entity DROP FOREIGN KEY FK_C7DE5CEF40C86FCE');
        $this->addSql('CREATE TABLE publisher_entity (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, surname VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, password_hash VARCHAR(60) NOT NULL COLLATE utf8mb4_unicode_ci, account_balance DOUBLE PRECISION NOT NULL, company VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, company_website VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE subscription_entity');
        $this->addSql('DROP TABLE video_player_entity');
        $this->addSql('ALTER TABLE customer CHANGE account_balance balance DOUBLE PRECISION NOT NULL');
    }
}
