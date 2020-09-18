<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $blocked = false;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTimeInterface $registered;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTimeInterface $removed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $forgottenPasswordToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sentForgottenPassword;

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
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
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
     * @see UserInterface
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
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    /**
     * @return DateTimeInterface
     */
    public function getRegistered(): DateTimeInterface
    {
        return $this->registered;
    }

    /**
     * @param DateTimeInterface $registered
     */
    public function setRegistered(DateTimeInterface $registered): void
    {
        $this->registered = $registered;
    }

    /**
     * @return DateTimeInterface
     */
    public function getRemoved(): DateTimeInterface
    {
        return $this->removed;
    }

    /**
     * @param DateTimeInterface $removed
     */
    public function setRemoved(DateTimeInterface $removed): void
    {
        $this->removed = $removed;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getForgottenPasswordToken(): string
    {
        return $this->forgottenPasswordToken;
    }

    public function setForgottenPasswordToken(string $forgottenPasswordToken): self
    {
        $this->forgottenPasswordToken = $forgottenPasswordToken;

        return $this;
    }

    public function getSentForgottenPassword(): string
    {
        return $this->sentForgottenPassword;
    }

    public function setSentForgottenPassword(string $sentForgottenPassword): self
    {
        $this->sentForgottenPassword = $sentForgottenPassword;

        return $this;
    }
}
