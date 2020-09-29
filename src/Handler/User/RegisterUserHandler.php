<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\HandlerInterface;
use App\Repository\UserRepository;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Throwable;

final class RegisterUserHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        LoggerInterface $logger
    )
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger          = $logger;
    }

    public function createUserFromUserDto(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $userDTO->password
        ));
        $user->setRegistered(new DateTime());
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    public function handle(Object $command): User
    {
        try {
            $user = $this->createUserFromUserDto($command);

            return $this->userRepository->saveUser($user);
        } catch (Throwable $e) {
            $this->logger->critical("Unable to register user. Please, try again.", [
                'exception' => $e->getMessage(),
                'email'     => $command->email
            ]);

            throw new ApiException(
                'Unable to register user. Please, try again.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}