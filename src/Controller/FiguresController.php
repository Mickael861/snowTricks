<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Figures;
use App\Form\FiguresType;
use App\Entity\Discussions;
use App\Form\DiscussionsType;
use App\Services\ServicesFigures;
use App\Services\ServicesDiscussions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FiguresController extends AbstractController
{
    private $serviceFigures;
    private $manager;

    public function __construct(ServicesFigures $serviceFigures, ManagerRegistry $manager)
    {
        $this->serviceFigures = $serviceFigures;
        $this->manager = $manager;
    }
    /**
     * @Route("/home", name="app_figures")
     * @Route("/", name="app_figures")
     */
    public function figuresAction(): Response
    {
        $success = null;

        //Récuperation des figures
        $repository = $this->manager->getRepository(Figures::class);
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
    public function createAction(Request $request): Response
    {
        if (is_null($this->getUser())) {
            $this->addFlash('errors', "Vous n'avez pas accés à cette partie du site");

            return $this->redirectToRoute('app_figures');
        }

        $figures = new Figures;
        
        $formFigures = $this->createForm(FiguresType::class, $figures);
        $formFigures->handleRequest($request);

        if ($formFigures->isSubmitted() && $formFigures->isValid()) {
            $is_valide = $this->serviceFigures->isValideFigureImages($formFigures->get('figuresImages'));
            
            if ($is_valide) {
                $this->serviceFigures->saveAllDatasFigures($formFigures, $figures);

                $this->addFlash('success', "Votre Figure a été créé avec succès");

                return $this->redirectToRoute('app_figure', ['id' => $figures->getId(), 'slug' => $figures->getSlug()]);
            } else {
                $this->addFlash('errors_file', "Veuillez remplir correctement le/les champ(s) image");
            }
        }

        return $this->render('figures/create.html.twig', [
            'formFigures' => $formFigures->createView(),
            'title'       => 'Création'
        ]);
    }

    
    /**
     * @Route("/update/figure/{id}", name="app_update")
     */
    public function updateAction(Request $request, Figures $figures): Response
    {
        $errors = [
            "user_empty" => "Vous n'avez pas accés à cette partie du site",
            "errors_user_id" => "Vous ne pouvez pas modifier cette figure"
        ];
        $error_user = $this->serviceFigures->redirectErrorsUser($figures, $errors);
        if ($error_user) {
            return $this->redirectToRoute('app_figures');
        }
        
        $collectionImages = new ArrayCollection;
        foreach ($figures->getFiguresImages() as $image) {
            $image->setFile(new File($this->getParameter('images_directory') . "/" . $image->getFilePath()));
            $collectionImages->add($image);
        }
        
        $formFigures = $this->createForm(FiguresType::class, $figures);
        $formFigures->handleRequest($request);

        $this->serviceFigures->updateDeleteImageDirectory($collectionImages, $figures);
        if ($formFigures->isSubmitted() && $formFigures->isValid()) {
            $is_valide = $this->serviceFigures->saveAllDatasFigures($formFigures, $figures, true);

            if ($is_valide) {
                $this->addFlash('success', "Votre Figure a été modifiée avec succès");

                return $this->redirectToRoute('app_figure', ['id' => $figures->getId(), 'slug' => $figures->getSlug()]);
            } else {
                $this->addFlash('errors_file', "Veuillez remplir correctement le/les champ(s) image");
            }
        }

        return $this->render('figures/create.html.twig', [
            'formFigures' => $formFigures->createView(),
            'title'       => 'Modification'
        ]);
    }

    /**
     * @Route("/delete/figure/{id}", name="app_delete")
     */
    public function delete(Figures $figures): RedirectResponse 
    {
        $errors = [
            "user_empty" => "Vous n'avez pas accés à cette partie du site",
            "errors_user_id" => "Vous ne pouvez pas supprimer cette figure"
        ];
        $error_user = $this->serviceFigures->redirectErrorsUser($figures, $errors);
        if ($error_user) {
            return $this->redirectToRoute('app_figures');
        }

        $fileSystem = new FileSystem();
        foreach ($figures->getFiguresImages() as $image) {
            $file_path = $this->getParameter('images_directory') . "/" . $image->getFilePath();
            if ($fileSystem->exists($file_path)) {
                $fileSystem->remove($file_path);
            }
        }

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->remove($figures);
        $managerRegistry->flush();

        $this->addFlash('success', "Suppression de votre figure effectuée avec succés");

        return $this->redirectToRoute('app_figures');
    }
}
