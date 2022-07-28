<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Figures;
use App\Form\FiguresType;
use App\Entity\Discussions;
use App\Form\DiscussionsType;
use App\Services\ServicesDiscussions;
use App\Services\ServicesFigures;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FiguresController extends AbstractController
{
    /**
     * @Route("/home", name="app_figures")
     * @Route("/", name="app_figures")
     */
    public function figuresAction(ManagerRegistry $manager): Response
    {
        $success = null;

        //Récuperation des figures
        $repository = $manager->getRepository(Figures::class);
        $figures = $repository->findBy(array(), array('id' => 'ASC'));

        return $this->render('figures/figures.html.twig', [
            'figures' => $figures,
            'success' => $success
        ]);
    }

    /**
     * @Route("/figure/{id}/{slug}", name="app_figure")
     */
    public function figureAction(Figures $figure, Request $request, ServicesDiscussions $servicesDiscussions): Response
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

        return $this->render('figures/figure.html.twig', [
            'figure' => $figure,
            'formDiscussions' => $formDiscussions->createView()
        ]);
    }

    /**
     * @Route("/create/figure", name="app_create")
     */
    public function createAction(Request $request, ServicesFigures $serviceFigures)
    {
        $figures = new Figures;

        $formFigures = $this->createForm(FiguresType::class, $figures);

        $formFigures->handleRequest($request);

        if ($formFigures->isSubmitted() && $formFigures->isValid()) {
            $serviceFigures->addFigures($formFigures, $figures);

            return $this->redirectToRoute('app_figure', ['id' => $figures->getId(), 'slug' => $figures->getSlug()]);
        }

        return $this->render('figures/create.html.twig', [
            'formFigures' => $formFigures->createView()
        ]);
    }
}
