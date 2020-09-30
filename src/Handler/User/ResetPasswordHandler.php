<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\Exception\ApiException;
use App\CommandBus\HandlerInterface;
use App\Repository\UserRepository;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class ResetPasswordHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function findUserByPasswordToken(string $token): User
    {
        $user = $this->userRepository->findByPasswordToken($token);

        if ($user === null) {
            throw new ApiException(
                'The token has been already used.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $user;
    }

    protected function isTokenExpired(DateTimeInterface $sentForgottenPasswordDate): void
    {
        $sentForgottenPasswordDate->add(new DateInterval('PT7H'));

        if ($sentForgottenPasswordDate < new DateTimeImmutable()) {
            throw new ApiException(
                'The password token has expired.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    protected function upgradePassword(User $user, string $newPassword): void
    {
        $newEncodedPassword = $this->passwordEncoder->encodePassword(
            $user,
            $newPassword
        );

        $this->userRepository->upgradePassword($user, $newEncodedPassword);
    }

    protected function saveUser(User $user): User
    {
        $user->setForgottenPasswordToken(null);
        $user->setSentForgottenPassword(null);

        $this->userRepository->saveUser($user);

        return $user;
    }

    public function handle(object $command): User
    {
        $user = $this->findUserByPasswordToken($command->token);

        $this->isTokenExpired($user->getSentForgottenPassword());
        $this->upgradePassword($user, $command->password);
        $this->saveUser($user);

        return $user;
    }
}