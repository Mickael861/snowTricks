<?php

namespace App\Entity;

use App\Repository\FiguresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FiguresRepository::class)
 */
class Figures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="figures_groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=FiguresGroups::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure_group_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=FiguresImages::class, mappedBy="figure_id", orphanRemoval=true)
     */
    private $figuresImages;

    /**
     * @ORM\OneToMany(targetEntity=FiguresVideos::class, mappedBy="figure_id", orphanRemoval=true)
     */
    private $figuresVideos;

    /**
     * @ORM\OneToMany(targetEntity=Discussions::class, mappedBy="figure_id")
     */
    private $discussions;

    public function __construct()
    {
        $this->figuresImages = new ArrayCollection();
        $this->figuresVideos = new ArrayCollection();
        $this->discussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFigureGroupId(): ?FiguresGroups
    {
        return $this->figure_group_id;
    }

    public function setFigureGroupId(?FiguresGroups $figure_group_id): self
    {
        $this->figure_group_id = $figure_group_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, FiguresImages>
     */
    public function getFiguresImages(): Collection
    {
        return $this->figuresImages;
    }

    public function addFiguresImage(FiguresImages $figuresImage): self
    {
        if (!$this->figuresImages->contains($figuresImage)) {
            $this->figuresImages[] = $figuresImage;
            $figuresImage->setFigureId($this);
        }

        return $this;
    }

    public function removeFiguresImage(FiguresImages $figuresImage): self
    {
        if ($this->figuresImages->removeElement($figuresImage)) {
            // set the owning side to null (unless already changed)
            if ($figuresImage->getFigureId() === $this) {
                $figuresImage->setFigureId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FiguresVideos>
     */
    public function getFiguresVideos(): Collection
    {
        return $this->figuresVideos;
    }

    public function addFiguresVideo(FiguresVideos $figuresVideo): self
    {
        if (!$this->figuresVideos->contains($figuresVideo)) {
            $this->figuresVideos[] = $figuresVideo;
            $figuresVideo->setFigureId($this);
        }

        return $this;
    }

    public function removeFiguresVideo(FiguresVideos $figuresVideo): self
    {
        if ($this->figuresVideos->removeElement($figuresVideo)) {
            // set the owning side to null (unless already changed)
            if ($figuresVideo->getFigureId() === $this) {
                $figuresVideo->setFigureId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Discussions>
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function addDiscussion(Discussions $discussion): self
    {
        if (!$this->discussions->contains($discussion)) {
            $this->discussions[] = $discussion;
            $discussion->setFigureId($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussions $discussion): self
    {
        if ($this->discussions->removeElement($discussion)) {
            // set the owning side to null (unless already changed)
            if ($discussion->getFigureId() === $this) {
                $discussion->setFigureId(null);
            }
        }

        return $this;
    }
}
