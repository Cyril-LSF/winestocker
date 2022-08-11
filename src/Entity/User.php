<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class),
ORM\HasLifecycleCallbacks()]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true),
    Assert\NotBlank([], "Veuillez saisir une adresse email"),
    Assert\Email([], "L'adresse email '{{ value }}' n'est pas valide."),
    Assert\Regex(
        pattern: "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
        match: true,
        message: "Adresse email non valide.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column,
    Assert\NotBlank([], "Veuillez saisir un mot de passe"),
    Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/i",
        match: true,
        message: "le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial")]
    private ?string $password = null;

    #[ORM\Column(length: 40),
    Assert\NotBlank([], "Veuillez saisir un Prénom"),
    Assert\Length(
    min: 3,
    max: 40,
    minMessage: "Le nom doit contenir au minimum {{ limit }} caractères",
    maxMessage: "Le nom doit contenir maximum {{ limit }} caractères"),
    Assert\Regex(
        pattern: "/^[a-z-\s]{3,40}$/i",
        match: true,
        message: "Le prénom ne peut contenir que des lettres, des tirets et des espaces")]
    private ?string $firstname = null;

    #[ORM\Column(length: 90),
    Assert\NotBlank([], "Veuillez saisir un nom"),
    Assert\Length(
    min: 3,
    max: 90,
    minMessage: "Le nom doit contenir au minimum {{ limit }} caractères",
    maxMessage: "Le nom doit contenir maximum {{ limit }} caractères"),

    Assert\Regex(
        pattern: "/^[a-z-\s]{3,90}$/i",
        match: true,
        message: "Le nom ne peut contenir que des lettres, des tirets et des espaces")]
    private ?string $lastname = null;

    #[ORM\Column(length: 91)]
    private ?string $screenname = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

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
        return (string) $this->email;
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getScreenname(): ?string
    {
        return $this->screenname;
    }

    #[ORM\PrePersist]
    public function setScreenname(): self
    {
        $this->screenname = $this->firstname . '.' . strtoupper(substr($this->lastname, 0, 1));

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

}
