<?php

namespace App\Repository;

use App\Entity\FiguresImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FiguresImages>
 *
 * @method FiguresImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method FiguresImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method FiguresImages[]    findAll()
 * @method FiguresImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiguresImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FiguresImages::class);
    }

    public function add(FiguresImages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FiguresImages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
