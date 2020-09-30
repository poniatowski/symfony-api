<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\CommandBus\HandlerInterface;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Security;

final class CloseAccountHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security       = $security;
    }

    public function handle(Object $command): User
    {
        $user = $this->security->getUser();

        $user->setClosed(true);
        $user->setClosedDate(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        return $user;
    }
}