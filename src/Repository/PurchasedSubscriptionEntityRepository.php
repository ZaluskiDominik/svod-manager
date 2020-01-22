<?php

namespace App\Repository;

use App\Entity\PurchasedSubscriptionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PurchasedSubscriptionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchasedSubscriptionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchasedSubscriptionEntity[]    findAll()
 * @method PurchasedSubscriptionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasedSubscriptionEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchasedSubscriptionEntity::class);
    }

    public function remove(int $purchasedSubId)
    {
        $this->getEntityManager()->createQuery("DELETE FROM App\Entity\PurchasedSubscriptionEntity ps
            WHERE ps.id = :id")->setParameter('id', $purchasedSubId)->execute();
    }
}
