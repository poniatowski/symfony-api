<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $blocked;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registred;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $removed;

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

    public function getRegistred(): ?\DateTimeInterface
    {
        return $this->registred;
    }

    public function setRegistred(\DateTimeInterface $registred): self
    {
        $this->registred = $registred;

        return $this;
    }

    public function getRemoved(): ?\DateTimeInterface
    {
        return $this->removed;
    }

    public function setRemoved(?\DateTimeInterface $removed): self
    {
        $this->removed = $removed;

        return $this;
    }
}
