<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191224200533 extends AbstractMigration
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
        $this->addSql('CREATE UNIQUE INDEX idx_p_email ON publisher (email)');
        $this->addSql('CREATE UNIQUE INDEX idx_company ON publisher (company)');
        $this->addSql('CREATE UNIQUE INDEX idx_c_email ON customer (email)');
        $this->addSql('CREATE FULLTEXT INDEX idx_fulltext_title ON video (title)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX idx_c_email ON customer');
        $this->addSql('DROP INDEX idx_p_email ON publisher');
        $this->addSql('DROP INDEX idx_company ON publisher');
        $this->addSql('ALTER TABLE publisher CHANGE company_website company_website VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX idx_fulltext_title ON video');
    }
}
