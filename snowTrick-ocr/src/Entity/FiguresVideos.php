<?php

namespace App\Entity;

use App\Repository\FiguresVideosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FiguresVideosRepository::class)
 */
class FiguresVideos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="figuresVideos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $site_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFigureId(): ?Figures
    {
        return $this->figure_id;
    }

    public function setFigureId(?Figures $figure_id): self
    {
        $this->figure_id = $figure_id;

        return $this;
    }

    public function getSiteUrl(): ?string
    {
        return $this->site_url;
    }

    public function setSiteUrl(string $site_url): self
    {
        $this->site_url = $site_url;

        return $this;
    }
}
