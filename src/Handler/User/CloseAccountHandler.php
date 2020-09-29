<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;

final class CloseAccountHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function saveUser(User $user): User
    {
        $user->setClosed(true);
        $user->setClosedDate(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        return $user;
    }
}