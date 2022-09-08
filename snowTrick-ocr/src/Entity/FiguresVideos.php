<?php

namespace App\Entity;

use App\Repository\FiguresVideosRepository;
use Symfony\Component\Validator\Constraints as Assert;
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
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $site_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFigure(): ?Figures
    {
        return $this->figure;
    }

    public function setFigure(?Figures $figure): self
    {
        $this->figure = $figure;

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
