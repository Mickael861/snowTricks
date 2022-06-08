<?php

namespace App\Entity;

use App\Repository\FiguresImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FiguresImagesRepository::class)
 */
class FiguresImages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="figuresImages")
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file_path;

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

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): self
    {
        $this->file_path = $file_path;

        return $this;
    }
}
