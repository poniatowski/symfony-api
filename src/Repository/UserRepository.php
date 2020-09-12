<?php

namespace App\Repository;

use App\Entity\User;
use App\DTO\User as UserDTO;
use DateTIme;
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

    public function saveUser(UserDTO $userDTO): void
    {
        $user = new User();
        $user->setName($userDTO->name);
        $user->setEmail($userDTO->email);
        $user->setPassword($userDTO->password);
        $user->setRegistered(new DateTime());

        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function findByEmailAddress(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
