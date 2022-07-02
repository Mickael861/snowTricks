<?php

namespace App\Services;

use DateTime;
use App\Entity\Users;
use DateTimeImmutable;
use App\Entity\Figures;
use App\Entity\Discussions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesDiscussions extends AbstractController
{
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    public function addDiscussions(Discussions $discussions, Figures $figure, Users $user)
    {
        $dateCreate = new DateTimeImmutable();
        $dateCreate->format('Y-m-d H:m:s');

        $dateUpdate = new DateTime();

        $discussions
            ->setFigure($figure)
            ->setUser($user)
            ->setCreatedAt($dateCreate)
            ->setUpdatedAt($dateUpdate);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($discussions);
        $managerRegistry->flush();
    }
}