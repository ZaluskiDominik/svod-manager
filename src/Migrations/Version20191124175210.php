<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124175210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, title VARCHAR(60) NOT NULL, embed_code LONGTEXT NOT NULL, description LONGTEXT NOT NULL, poster_url VARCHAR(100) NOT NULL, INDEX IDX_7CC7DA2C40C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_entity_subscription_entity (video_entity_id INT NOT NULL, subscription_entity_id INT NOT NULL, INDEX IDX_5801D606435A00C8 (video_entity_id), INDEX IDX_5801D606B39ABFE1 (subscription_entity_id), PRIMARY KEY(video_entity_id, subscription_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('ALTER TABLE video_entity_subscription_entity ADD CONSTRAINT FK_5801D606435A00C8 FOREIGN KEY (video_entity_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_entity_subscription_entity ADD CONSTRAINT FK_5801D606B39ABFE1 FOREIGN KEY (subscription_entity_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE video_entity_subscription_entity DROP FOREIGN KEY FK_5801D606435A00C8');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_entity_subscription_entity');
        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
