<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Figures;
use App\Entity\Discussions;
use App\Form\DiscussionsType;
use App\Services\ServicesDiscussions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function figure(Figures $figure, Request $request, ServicesDiscussions $servicesDiscussions): Response
    {
        $discussions = new Discussions;
        $formDiscussions = $this->createForm(DiscussionsType::class, $discussions);

        $formDiscussions->handleRequest($request);

        /**
         * @var Users $user
         */
        $user = $this->getUser();
        if ($formDiscussions->isSubmitted() && $formDiscussions->isValid() && !empty($user->getId())) {
            $servicesDiscussions->addDiscussions($discussions, $figure, $user);

            $this->addFlash('success', "Commentaire enregistré avec succés");

            return $this->redirectToRoute('app_figure', ['id' => $figure->getId(), 'slug' => $figure->getSlug()]);
        }

        return $this->render('home/figure.html.twig', [
            'figure' => $figure,
            'formDiscussions' => $formDiscussions->createView()
        ]);
    }
}
