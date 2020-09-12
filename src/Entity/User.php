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
    protected ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $password;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    protected bool $blocked = false;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected DateTimeInterface $registered;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected DateTimeInterface $removed;

    /**
     * @return int
     */
    public function getId(): int
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
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
