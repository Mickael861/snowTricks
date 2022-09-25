<?php
namespace App\Services;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesSecurity extends AbstractController
{
    public function __construct(ManagerRegistry $manager, ServicesUsers $servicesUsers)
    {
        $this->manager = $manager;
        $this->servicesUsers = $servicesUsers;
    }
        
    
    /**
     * changePasswordManagement
     *
     * @param string $token Token sent by email to change the password
     * @param string $password new password
     * @param array $addFlash flash message
     * @param bool $is_valide true if the password is valid, false otherwise
     * @param string $errors the error message
     *
     */
    public function changePasswordManagement(
        string $token,
        string $password,
        array &$addFlash,
        bool &$is_valide,
        ?string &$errors
    ) {
        if (!empty($token)) {
            $repository = $this->manager->getRepository(Users::class);
    
            $users = $repository->findOneBy([
                'token' => $token
            ]);
            
            if (!empty($users)) {
                if (isset($_POST['password'])) {
                    if ($password == null) {
                        $is_valide = false;
                        $errors = 'Le mot de passe est obligatoire';
                    } else {
                        $this->servicesUsers->saveNewPassword($users, $password);
        
                        $addFlash['success'] = 'Votre mot de passe a été correctement modifié';
                    }
                }
            } else {
                $addFlash['errors'] = 'Une erreur c\'est produite, validation du compte impossible';
            }
        }
    }
}
