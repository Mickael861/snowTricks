<?php

namespace App\Controller;

use App\Entity\Figures;
use App\Form\DiscussionsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_figures")
     * @Route("/", name="app_figures")
     */
    public function figures(ManagerRegistry $manager): Response
    {
        $success = null;

        //Récuperation des figures
        $repository = $manager->getRepository(Figures::class);
        $figures = $repository->findBy(array(), array('id' => 'ASC'));

        return $this->render('home/figures.html.twig', [
            'figures' => $figures,
            'success' => $success
        ]);
    }

    /**
     * @Route("/figure/{id}/{slug}", name="app_figure")
     */
    public function figure(Figures $figure): Response
    {
        $formDiscussions = $this->createForm(DiscussionsType::class);

        return $this->render('home/figure.html.twig', [
            'figure' => $figure,
            'formDiscussions' => $formDiscussions->createView()
        ]);
    }
}
