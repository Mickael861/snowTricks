<?php

namespace App\Services\Save;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SaveNewPassword extends AbstractController
{
    
    public function __construct(
        ManagerRegistry $manager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->manager = $manager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * saving new password
     *
     * @param  Users $users Entity Users
     * @param  string $password New password
     */
    public function save(Users $users, string $password)
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $users,
            $password
        );

        $users
            ->setToken('')
            ->setPassword($hashedPassword);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();
    }
}
