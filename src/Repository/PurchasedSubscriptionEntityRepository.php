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

    // /**
    //  * @return PurchasedSubscriptionEntity[] Returns an array of PurchasedSubscriptionEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurchasedSubscriptionEntity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
