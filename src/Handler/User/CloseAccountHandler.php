<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\Handler\HandlerInterface;
use App\Repository\UserRepository;
use DateTimeImmutable;

final class CloseAccountHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Object $command): User
    {
        $command->setClosed(true);
        $command->setClosedDate(new DateTimeImmutable());
        $this->userRepository->saveUser($command);

        return $command;
    }
}