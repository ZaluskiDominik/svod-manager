<?php

namespace App\Repository;

use App\Entity\VideoPlayerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideoPlayerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoPlayerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoPlayerEntity[]    findAll()
 * @method VideoPlayerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoPlayerEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoPlayerEntity::class);
    }
}
