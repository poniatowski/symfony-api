<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\HandlerInterface;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Throwable;

final class UserDetailsHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    private Security $security;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        Security $security,
        LoggerInterface $logger
    )
    {
        $this->userRepository = $userRepository;
        $this->security       = $security;
        $this->logger         = $logger;
    }

    public function handle(Object $command): User
    {
        try {
            $user = $this->security->getUser();

            $user->setFirstName(ucwords($command->firstname));
            $user->setSurname(ucwords($command->surname));
            $this->userRepository->saveUser($user);
        } catch (Throwable $e) {
            $this->logger->critical("User details can't be updated.", [
                'exception' => $e->getMessage(),
                'email'     => $user->getUsername()
            ]);

            throw new ApiException(
                "User details can't be updated.",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $user;
    }
}