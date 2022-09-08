<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity("email", message = "L'email {{ value }} est deja utilisé")
 * @UniqueEntity("user_name", message = "Le pseudo {{ value }} est deja utilisé")
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     *
     * @Assert\Email(
     *     message = "L'email {{ value }} n'est pas valide"
     * )
     *
     * @Assert\NotNull(
     *     message = "L'email est obligatoire"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [
        "ROLE" => "ROLE_USER"
    ];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Assert\NotNull(
     *     message = "Le mot de passe est obligatoire"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull(
     *     message = "Le pseudo est obligatoire"
     * )
     *
     * @Assert\Length(
     *      min = 10,
     *      max = 20,
     *      minMessage = "Le pseudo doit contenir minimum {{ limit }} caractéres",
     *      maxMessage = "Le pseudo doit contenir maximum {{ limit }} caractéres"
     * )
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull(
     *     message = "La photo de profil est obligatoire"
     * )
     */
    private $file_path;

    /**
     * @ORM\Column(type="text")
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_validate = false;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Figures::class, mappedBy="user", orphanRemoval=true)
     */
    private $figures;

    /**
     * @ORM\OneToMany(targetEntity=Discussions::class, mappedBy="user", orphanRemoval=true)
     */
    private $discussions;

    public function __construct()
    {
        $this->figures = new ArrayCollection();
        $this->discussions = new ArrayCollection();

        date_default_timezone_set('Europe/Paris');
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->user_name;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->user_name;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isIsValidate(): ?bool
    {
        return $this->is_validate;
    }

    public function setIsValidate(bool $is_validate): self
    {
        $this->is_validate = $is_validate;

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

    /**
     * @return Collection<int, Figures>
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figures $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setUser($this);
        }

        return $this;
    }

    public function removeFigure(Figures $figure): self
    {
        if ($this->figures->removeElement($figure)) {
            // set the owning side to null (unless already changed)
            if ($figure->getUser() === $this) {
                $figure->setUser(null);
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
            $discussion->setUser($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussions $discussion): self
    {
        if ($this->discussions->removeElement($discussion)) {
            // set the owning side to null (unless already changed)
            if ($discussion->getUser() === $this) {
                $discussion->setUser(null);
            }
        }

        return $this;
    }
}
