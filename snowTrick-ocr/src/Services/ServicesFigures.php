<?php

namespace App\Services;

use App\Entity\Figures;
use App\Entity\FiguresGroups;
use App\Entity\FiguresImages;
use App\Entity\FiguresVideos;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

/**
 * Services Figures
 */
class ServicesFigures extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $manager)
    {
        $this->managerRegistry = $manager->getManager();
    }
    
    /**
     * add Figures
     *
     * @param  FormInterface $formFigures Form Figures
     * @param  Figures $figures Figures
     * @return void
     */
    public function addFigures(FormInterface $formFigures, Figures $figures): void
    {
        $figureName = $formFigures->get('name')->getData();
        $figureDesc = $formFigures->get('description')->getData();
        $figureGroup = $formFigures->get('figure_group')->getData();
        $this->saveFigures($figures, $figureName, $figureDesc, $figureGroup);

        $datasVideos = $formFigures->get('figuresVideos')->getData();
        $this->saveFiguresVideos($figures, $datasVideos);

        $datasImages = $formFigures->get('figuresImages')->getData();
        $this->saveFiguresImages($figures, $datasImages);
    }
    
    /**
     * save figures
     *
     * @param  Figures $figures Figures
     * @param  string $figureName Name of the figure
     * @param  string $figureDesc Description of the figure
     * @param  FiguresGroups $figureGroup Entity FiguresGroup
     * @return void
     */
    private function saveFigures(
        Figures $figures,
        string $figureName,
        string $figureDesc,
        FiguresGroups $figureGroup
    ): void {
        $user = $this->getUser();

        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($figureName);

        $figures
            ->setUser($user)
            ->setName($figureName)
            ->setSlug($slug)
            ->setDescription($figureDesc)
            ->setFigureGroup($figureGroup);

        $this->managerRegistry->persist($figures);
        $this->managerRegistry->flush();
    }
    
    /**
     * save figures videos
     *
     * @param  Figures $figures Entity Figures
     * @param  ArrayCollection $datasVideos Collection of the videos
     * @return void
     */
    private function saveFiguresVideos(Figures $figures, ArrayCollection $datasVideos): void
    {
        $originalVideos = new ArrayCollection();

        foreach ($datasVideos as $video) {
            $originalVideos->add($video);
        }

        $figuresVideos = new FiguresVideos;
        foreach ($originalVideos as $video) {
            $figuresVideos
                ->setSiteUrl($video->getSiteUrl())
                ->setFigure($figures);
            
            $this->managerRegistry->persist($video);
        }

        $this->managerRegistry->persist($figuresVideos);
        $this->managerRegistry->flush();
    }
    
    /**
     * save figures images
     *
     * @param  Figures $figures Entity Figures
     * @param  ArrayCollection $datasImages Collection of the images
     * @return void
     */
    private function saveFiguresImages(Figures $figures, ArrayCollection $datasImages): void
    {
        $originalImages = new ArrayCollection();

        foreach ($datasImages as $image) {
            $originalImages->add($image);
        }

        $figuresImages = new FiguresImages;
        foreach ($originalImages as $image) {
            $figuresImages
                ->setfilePath($image->getFilePath())
                ->setFigure($figures);
            
            $this->managerRegistry->persist($image);
        }
        $this->managerRegistry->persist($figuresImages);
        $this->managerRegistry->flush();
    }
}
