<?php

namespace App\Repository;

use App\Entity\SubscriptionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SubscriptionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscriptionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscriptionEntity[]    findAll()
 * @method SubscriptionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionEntity::class);
    }

    public function createSubscription(SubscriptionEntity $subscription)
    {
        $videos = $subscription->getVideos();
        $subscription->setVideos(new ArrayCollection());

        $em = $this->getEntityManager();
        $em->persist($subscription);
        $em->flush($subscription);

        $subscription->setVideos($videos);
        $pdo = $em->getConnection()->getWrappedConnection();
        $stmt = $pdo->prepare("INSERT INTO 
            video_entity_subscription_entity(video_entity_id, subscription_entity_id)
            VALUES(?, ?)");
        foreach ($subscription->getVideos() as $video) {
            $stmt->execute([$video->getId(), $subscription->getId()]);
        }
    }

    public function findAllSubscriptions()
    {
        return $this->getEntityManager()
            ->createQuery("SELECT s, partial p.{id, company, companyWebsite}, v FROM App\Entity\SubscriptionEntity s
                LEFT JOIN s.videos v
                INNER JOIN s.publisher p")
            ->getArrayResult();
    }

    public function deleteSubscription(int $subId)
    {
        $this->getEntityManager()
            ->createQuery("DELETE FROM App\Entity\SubscriptionEntity s WHERE s.id = :id")
            ->setParameter('id', $subId)
            ->execute();
    }
}
