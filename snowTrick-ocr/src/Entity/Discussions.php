<?php

namespace App\Entity;

use App\Repository\DiscussionsRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscussionsRepository::class)
 */
class Discussions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="discussions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="discussions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotNull(
     *     message = "Le contenu est obligatoire"
     * )
     *
     * @Assert\Length(
     *      min = 50,
     *      max = 255,
     *      minMessage = "Le contenu doit contenir minimum {{ limit }} caractéres",
     *      maxMessage = "Le contenu doit contenir maximum {{ limit }} caractéres"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
