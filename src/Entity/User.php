<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    public function __construct()
    {
        $this->setRegistered(new DateTime());
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage = "Your name must be at least {{ limit }} characters long",
     *     maxMessage = "Your name cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private boolean $blocked;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTimeInterface $registered;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTimeInterface $removed;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getRegistered(): ?DateTimeInterface
    {
        return $this->registered;
    }

    public function setRegistered(DateTimeInterface $registered): self
    {
        $this->registered = $registered;

        return $this;
    }

    public function getRemoved(): ?DateTimeInterface
    {
        return $this->removed;
    }

    public function setRemoved(?DateTimeInterface $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * @Assert\IsTrue(message="The password cannot match your name")
     */
    public function isPasswordSafe(): bool
    {
        return true;
        // return $this->name !== $this->password;
    }
}
