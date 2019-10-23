<?php

namespace App\Repository;

use App\Entity\PublisherEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PublisherEntity|null find($id, $lockMode = null, $lockVersion = null)    public function findByEmailAndPassword(string $email, string $password): ?PublisherEntity

 * @method PublisherEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublisherEntity[]    findAll()
 * @method PublisherEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublisherEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublisherEntity::class);
    }

    public function findByEmailAndPassword(string $email, string $password): ?PublisherEntity
    {
        $publisher = $this->getEntityManager()->createQuery("SELECT p FROM App\Entity\PublisherEntity p
            WHERE p.email = :email")
            ->setParameter('email', $email)
            ->getOneOrNullResult();

        if ($publisher === null) {
            return null;
        }

        return (password_verify($password, $publisher->getPasswordHash())) ? $publisher : null;
    }
}
