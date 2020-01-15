<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115205913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('drop procedure purchase_sub;');
        $this->addSql('CREATE PROCEDURE purchase_sub(subscriptionId INT, customerId INT)
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
            BEGIN
                ROLLBACK;
                RESIGNAL;
            END;

            START TRANSACTION;
            
            /* Increase publisher\'s account balance*/
            UPDATE publisher p
            INNER JOIN subscription s ON s.publisher_id = p.id
            SET p.account_balance = p.account_balance + s.price
            WHERE s.id = subscriptionId;
        
            /* Decrease customer\'s account balance */
            UPDATE customer c
            SET c.account_balance =
            c.account_balance - (SELECT s.price FROM subscription s WHERE s.id = subscriptionId)
            WHERE c.id = customerId;
        
            /* Entry for purchased subscription */
            INSERT INTO
            purchased_subscription(subscription_id, customer_id, price, start_date, active_to)
            VALUES(
                      subscriptionId,
                      customerId,
                      (SELECT price FROM subscription WHERE id = subscriptionId),
                      CURRENT_DATE,
                      DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
                  );
                  
            COMMIT;
        END;
        ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('drop procedure purchase_sub;');
        $this->addSql('CREATE PROCEDURE purchase_sub(subscriptionId INT, customerId INT)
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
            DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;

            START TRANSACTION;
            
            /* Increase publisher\'s account balance*/
            UPDATE publisher p
            INNER JOIN subscription s ON s.publisher_id = p.id
            SET p.account_balance = p.account_balance + s.price
            WHERE s.id = subscriptionId;
        
            /* Decrease customer\'s account balance */
            UPDATE customer c
            SET c.account_balance =
            c.account_balance - (SELECT s.price FROM subscription s WHERE s.id = subscriptionId)
            WHERE c.id = customerId;
        
            /* Entry for purchased subscription */
            INSERT INTO
            purchased_subscription(subscription_id, customer_id, price, start_date, active_to)
            VALUES(
                      subscriptionId,
                      customerId,
                      (SELECT price FROM subscription WHERE id = subscriptionId),
                      CURRENT_DATE,
                      DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
                  );
                  
            COMMIT;
        END;
        ');
    }
}
