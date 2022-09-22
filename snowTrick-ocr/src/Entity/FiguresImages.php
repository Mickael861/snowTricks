<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

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

    /**
     * file_path alias for form
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;
        
        return $this;
    }

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

    public function setFilePath(String $file_path): self
    {
        $this->file_path = $file_path;

        return $this;
    }
}
