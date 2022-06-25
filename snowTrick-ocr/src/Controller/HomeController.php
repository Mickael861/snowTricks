<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Figures;
use App\Entity\Discussions;
use App\Form\DiscussionsType;
use DateTime;
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
    public function figure(Figures $figure, Request $request, ManagerRegistry $manager): Response
    {
        $discussions = new Discussions;
        $formDiscussions = $this->createForm(DiscussionsType::class, $discussions);

        $formDiscussions->handleRequest($request);

        $user = $this->getUser();
        if ($formDiscussions->isSubmitted() && $formDiscussions->isValid() && !empty($user->getId())) {
            $dateCreate = new DateTimeImmutable();
            $dateCreate->format('Y-m-d H:m:s');

            $dateUpdate = new DateTime();

            $discussions
                ->setFigure($figure)
                ->setUser($user)
                ->setCreatedAt($dateCreate)
                ->setUpdatedAt($dateUpdate);

            $managerRegistry = $manager->getManager();
            $managerRegistry->persist($discussions);
            $managerRegistry->flush();

            $this->addFlash('success', "Commentaire enregistré avec succés");

            return $this->redirectToRoute('app_figure', ['id' => $figure->getId(), 'slug' => $figure->getSlug()]);
        }

        return $this->render('home/figure.html.twig', [
            'figure' => $figure,
            'formDiscussions' => $formDiscussions->createView()
        ]);
    }
}
