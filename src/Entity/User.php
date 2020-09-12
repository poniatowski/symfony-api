<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

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
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param callable $encoder
     * @param string $plainPassword
     * @return User
     */
    public function setPassword(callable $encoder, string $plainPassword): self
    {
        $this->password = (string)$encoder($plainPassword);
        return $this;
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
}
