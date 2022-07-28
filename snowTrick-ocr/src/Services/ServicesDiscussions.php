<?php

namespace App\Services;

use App\Entity\Users;
use App\Entity\Figures;
use App\Entity\Discussions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Service discussions
 */
class ServicesDiscussions extends AbstractController
{
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * addDiscussions
     *
     * @param  Discussions $discussions Entity discussions
     * @param  Figures $figure Entity Figures
     * @param  Users $user logged in user
     * @return void
     */
    public function addDiscussions(Discussions $discussions, Figures $figure, Users $user): void
    {
        $discussions
            ->setFigure($figure)
            ->setUser($user);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($discussions);
        $managerRegistry->flush();
    }
}
