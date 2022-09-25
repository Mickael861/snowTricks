<?php

namespace App\Repository;

use App\Entity\FiguresVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FiguresVideos>
 *
 * @method FiguresVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method FiguresVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method FiguresVideos[]    findAll()
 * @method FiguresVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiguresVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FiguresVideos::class);
    }

    public function add(FiguresVideos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FiguresVideos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
