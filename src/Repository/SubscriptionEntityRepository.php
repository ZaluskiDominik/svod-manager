<?php

namespace App\Repository;

use App\Common\Event\EventExchange;
use App\Common\Event\EventSender;
use App\Common\Event\SubscriptionPurchasedEvent;
use App\Entity\SubscriptionEntity;
use App\Exception\NotEnoughMoneyException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use PDO;
use PDOException;

/**
 * @method SubscriptionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscriptionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscriptionEntity[]    findAll()
 * @method SubscriptionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionEntityRepository extends ServiceEntityRepository
{
    /** @var EventSender */
    private $eventSender;

    public function __construct(ManagerRegistry $registry, EventSender $eventSender)
    {
        parent::__construct($registry, SubscriptionEntity::class);
        $this->eventSender = $eventSender;
    }

    public function createSubscription(SubscriptionEntity $subscription)
    {
        $videos = $subscription->getVideos();
        $subscription->setVideos(new ArrayCollection());

        $em = $this->getEntityManager();
        $em->persist($subscription);
        $em->flush($subscription);

        $subscription->setVideos($videos);
        /** @var PDO $pdo */
        $pdo = $em->getConnection()->getWrappedConnection();
        $stmt = $pdo->prepare("INSERT INTO 
            video_entity_subscription_entity(video_entity_id, subscription_entity_id)
            VALUES(?, ?)");
        foreach ($subscription->getVideos() as $video) {
            $stmt->execute([$video->getId(), $subscription->getId()]);
        }
    }

    public function findAllSubscriptions(): array
    {
        return $this->getEntityManager()
            ->createQuery("SELECT
                s, partial p.{id, company, companyWebsite}, partial v.{id, title, description, posterUrl}
                FROM App\Entity\SubscriptionEntity s
                LEFT JOIN s.videos v
                INNER JOIN s.publisher p")
            ->getArrayResult();
    }

    public function findAllSubscriptionsWithInfoIfPurchased(int $customerId): array
    {
        /** @var PDO $pdo */
        $pdo = $this->getEntityManager()->getConnection()->getWrappedConnection();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $pdo->prepare("CALL find_all_subs_with_info_if_purchased(?)");
        $stmt->execute([$customerId]);
        $subs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->getFormattedSubsWithInfoIfPurchased($subs);
    }

    public function deleteSubscription(int $subId)
    {
        $this->getEntityManager()
            ->createQuery("DELETE FROM App\Entity\SubscriptionEntity s WHERE s.id = :id")
            ->setParameter('id', $subId)
            ->execute();
    }

    /** @throws NotEnoughMoneyException */
    public function purchaseSubscription(int $subId, int $customerId)
    {
        /** @var PDO $pdo */
        $pdo = $this->getEntityManager()->getConnection()->getWrappedConnection();
        $stmt = $pdo->prepare("CALL purchase_sub(:subId, :customerId)");
        $stmt->bindValue('subId', $subId);
        $stmt->bindValue('customerId', $customerId);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new NotEnoughMoneyException();
        }

        $this->eventSender->send(
            new EventExchange(EventExchange::SUBSCRIPTIONS),
            new SubscriptionPurchasedEvent($subId, $customerId)
        );
    }

    private function getFormattedSubsWithInfoIfPurchased(array $subs): array
    {
        $subs[] = [ 'id' => -1 ];
        $formattedSubs = [];
        $videos = [];
        $prevSubId = $subs[0]['id'] ?? null;
        foreach ($subs as $index => $sub) {
            if ($sub['id'] !== $prevSubId) {
                $formattedSubs[] = [
                    'id' => $prevSubId,
                    'name' => $subs[$index - 1]['name'],
                    'price' => $subs[$index - 1]['price'],
                    'createdAt' => $subs[$index - 1]['created_at'],
                    'publisher' => [
                        'id' => $subs[$index - 1]['publisher_id'],
                        'company' => $subs[$index - 1]['company'],
                        'companyWebsite' => $subs[$index - 1]['company_website']
                    ],
                    'videos' => $videos,
                    'activeTo' => $subs[$index - 1]['active_to']
                ];

                $prevSubId = $sub['id'];
                $videos = [];
            }

            $videos[] = [
                'id' => $sub['video_id'] ?? null,
                'title' => $sub['title'] ?? null,
                'posterUrl' => $sub['poster_url'] ?? null
            ];
        }

        return $formattedSubs;
    }
}
