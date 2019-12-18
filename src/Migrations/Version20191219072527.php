<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219072527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE PROCEDURE find_all_subs_with_info_if_purchased(IN customerId INT)
            BEGIN
                SELECT s.*, p.id AS publisher_id, p.company, p.company_website,
                       v.id AS video_id, v.poster_url, v.title,
                       (
                           SELECT ps.active_to FROM purchased_subscription ps
                           WHERE ps.customer_id = customerId AND ps.subscription_id = s.id
                           ORDER BY ps.start_date DESC
                           LIMIT 1
                       ) AS active_to
                FROM subscription s
                         INNER JOIN publisher p on s.publisher_id = p.id
                         LEFT JOIN video_entity_subscription_entity vs ON vs.subscription_entity_id = s.id
                         LEFT JOIN video v ON v.id = vs.video_entity_id
                ORDER BY s.id;
            END;");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP PROCEDURE find_all_subs_with_info_if_purchased");
    }
}
