<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);

        $this->manager = $manager;
    }

    public function saveUser(string $name, string $email, string $password): void
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $this->manager->persist($user);
        $this->manager->flush();
    }
}
