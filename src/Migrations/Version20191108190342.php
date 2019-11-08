<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108190342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, name VARCHAR(40) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A3C664D340C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, template_embed_code LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D340C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('DROP TABLE subscription_entity');
        $this->addSql('DROP TABLE video_player_entity');
        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subscription_entity (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, name VARCHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, INDEX IDX_C7DE5CEF40C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE video_player_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, template_embed_code LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subscription_entity ADD CONSTRAINT FK_C7DE5CEF40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE video_player');
        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
