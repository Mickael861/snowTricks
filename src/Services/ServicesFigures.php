<?php

namespace App\Services;

use App\Entity\Users;
use App\Entity\Figures;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Services Figures
 */
class ServicesFigures extends AbstractController
{
    public function __construct(ManagerRegistry $manager)
    {
        $this->managerRegistry = $manager->getManager();
    }

        
    /**
     * save all datas of the figure
     *
     * @param  FormInterface $formFigures Form Figures
     * @param  Figures $figures Figures
     * @return void
     */
    public function saveAllDatasFigures(FormInterface $formFigures, Figures $figures, $update = false)
    {
        $this->saveFigures($formFigures, $figures);
        $this->saveFiguresVideos($formFigures, $figures);
        $this->saveFiguresImages($formFigures, $figures);

        foreach ($figures->getFiguresImages() as $image) {
            if ($image->getFilePath() === null) {
                return false;
            }
        }

        $this->managerRegistry->persist($figures);
        $this->managerRegistry->flush();

        return true;
    }
    
    /**
     * save Figures
     *
     * @param  FormInterface $formFigures Form Figures
     * @param  Figures $figures Figures
     * @return void
     */
    private function saveFigures(FormInterface $formFigures, Figures $figures): void
    {
        $user = $this->getUser();

        $slugger = new AsciiSlugger();
        $figureName = $formFigures->get('name')->getData();
        $slug = $slugger->slug($figureName);

        $figures
            ->setUser($user)
            ->setSlug($slug);
    }
    
    /**
     * save figures videos
     *
     * @param  FormInterface $formFigures Form Figures
     * @param  Figures $figures Figures
     * @return void
     */
    private function saveFiguresVideos(FormInterface $formFigures, Figures $figures): void
    {
        $datasVideos = $formFigures->get('figuresVideos')->getData();
        foreach ($datasVideos as $video) {
            if (!empty($video->getSiteUrl())) {
                $video
                ->setFigure($figures)
                ->setSiteUrl($video->getSiteUrl());
            
                $figures->addFiguresVideo($video);
            }
        }
    }
    
    /**
     * save figures images
     *
     * @param  FormInterface $formFigures Form Figures
     * @param  Figures $figures Figures
     * @return void
     */
    private function saveFiguresImages(FormInterface $formFigures, Figures $figures)
    {
        $formImages = $formFigures->get('figuresImages');
        foreach ($formImages as $image) {
            $figuresImages = $image->getData();
            $file_path = $image->get('file')->getData();
            if (!empty($file_path)) {
                $file = md5(uniqid()) . '.' . $file_path->guessExtension();

                $filesystem = new Filesystem();
                $filesystem->mkdir($this->getParameter('images_directory'));
                if (!$filesystem->exists($file)) {
                    $file_path->move($this->getParameter('images_directory'), $file);
                }
                
                $figuresImages
                    ->setFigure($figures)
                    ->setFilePath($file);
    
                $figures->addFiguresImage($figuresImages);
            }
        }
    }
    
    /**
     * handle errors related to the user's session
     *
     * @param  Figures $figures Figures entity
     * @param  array $errors Tableau de message d'erreur
     * @return bool if there is a user error return true if not false
     */
    public function redirectErrorsUser(Figures $figures, array $errors): bool
    {
        $error_user = false;
        /**
         * @var Users $user
         */
        $user = $this->getUser();
        if (is_null($user)) {
            $this->addFlash('errors', $errors['user_empty']);

            $error_user = true;
        } else {
            if ($user->getId() !== $figures->getUser()->getId()) {
                $this->addFlash('errors', $errors['errors_user_id']);

                $error_user = true;
            }
        }
        
        return $error_user;
    }
    
    /**
     * deleteImageDirectory
     *
     * @param  ArrayCollection $collectionImages Existing image collection before backup
     * @param  Figures $figures
     */
    public function updateDeleteImageDirectory(ArrayCollection $collectionImages, Figures $figures): void
    {
        $fileSystem = new FileSystem();
        foreach ($collectionImages as $image) {
            if (!$figures->getFiguresImages()->contains($image)) {
                $file_path = $this->getParameter('images_directory') . "/" . $image->getFilePath();
                if ($fileSystem->exists($file_path)) {
                    $fileSystem->remove($file_path);
                }
            }
        }
    }

    
    /**
     * valid the datas of form figuresImages
     *
     * @param  Objet $datasFigureImage datas of form figuresImages
     * @return bool $is_valide If data figuresImages is valid true, otherwise false
     */
    public function isValideFigureImages(Object $datasFigureImage)
    {
        $is_valide = false;

        foreach ($datasFigureImage as $figureImage) {
            if ($figureImage->get('file')->getData() !== null) {
                $is_valide = true;
            } else {
                $is_valide = false;

                break;
            }
        }

        return $is_valide;
    }
}
