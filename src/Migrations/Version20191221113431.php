<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191221113431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD video_player_id INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C5B99884A FOREIGN KEY (video_player_id) REFERENCES video_player (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C5B99884A ON video (video_player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT \'\'NULL\'\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C5B99884A');
        $this->addSql('DROP INDEX IDX_7CC7DA2C5B99884A ON video');
        $this->addSql('ALTER TABLE video DROP video_player_id');
    }
}
