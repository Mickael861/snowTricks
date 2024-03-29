<?php

namespace App\Repository;

use App\Entity\FiguresGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FiguresGroups>
 *
 * @method FiguresGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method FiguresGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method FiguresGroups[]    findAll()
 * @method FiguresGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiguresGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FiguresGroups::class);
    }

    public function add(FiguresGroups $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FiguresGroups $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
