<?php

namespace App\Controller;

use App\Entity\Figures;
use App\Entity\FiguresImages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_figures_home")
     * @Route("/", name="app_figures_home")
     * 
     */    
    public function figuresHome(ManagerRegistry $manager, Request $request): Response
    {
        $request = Request::createFromGlobals();
        
        //Récuperation des figures
        $repository = $manager->getRepository(Figures::class);

        $figures = $repository->findBy(array(), array('id' => 'ASC'));

        return $this->render('home/index.html.twig', [
            'figures' => $figures
        ]);
    }
}
