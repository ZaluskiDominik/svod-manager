<?php

namespace App\Repository;

use App\Entity\VideoEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideoEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoEntity[]    findAll()
 * @method VideoEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoEntity::class);
    }

    public function findCustomerVideos(int $customerId): array
    {
        return $this->getEntityManager()->createQuery("SELECT
            v.id, v.title, v.description, v.embedCode, v.posterUrl, p.company
            FROM App\Entity\VideoEntity v
            INNER JOIN v.subscriptions s
            INNER JOIN s.purchasedSubscriptions ps
            INNER JOIN ps.customer c
            INNER JOIN v.publisher p
            WHERE c.id = :customerId
            GROUP BY v.id"
        )->setParameter("customerId", $customerId)
            ->getArrayResult();
    }

    public function findPublisherVideos(int $publisherId): array
    {
        return $this->getEntityManager()->createQuery("SELECT
            App\Entity\VideoEntity v")->getArrayResult();
    }
}
