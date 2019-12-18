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
}
