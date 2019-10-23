<?php

namespace App\Repository;

use App\Entity\CustomerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerEntity[]    findAll()
 * @method CustomerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerEntity::class);
    }

    public function findByEmailAndPassword(string $email, string $password): ?CustomerEntity
    {
        $customer = $this->getEntityManager()->createQuery("SELECT c FROM App\Entity\CustomerEntity c
            WHERE c.email = :email")
            ->setParameter('email', $email)
            ->getOneOrNullResult();

        if ($customer === null) {
            return null;
        }

        return (password_verify($password, $customer->getPasswordHash())) ? $customer : null;
    }
}
