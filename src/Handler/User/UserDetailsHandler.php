<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class UserDetailsHandler
{
    private UserRepository $userRepository;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        LoggerInterface $logger
    )
    {
        $this->userRepository = $userRepository;
        $this->logger         = $logger;
    }

    public function saveUser(User $user, UserDTO $userDetailsDTO): User
    {
        try {
            $user->setFirstName(ucwords($userDetailsDTO->firstname));
            $user->setSurname(ucwords($userDetailsDTO->surname));
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