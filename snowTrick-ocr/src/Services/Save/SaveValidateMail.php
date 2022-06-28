<?php

namespace App\Services\Save;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaveValidateMail extends AbstractController
{
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Registration and processing of user backup
     *
     * @param Users $users Vérification 
     */
    public function save(Users $users)
    {
        $users
            ->setIsValidate(true)
            ->setToken('');

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();
    }
}
