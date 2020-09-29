<?php declare(strict_types = 1);

namespace App\Handler\User;

use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\HandlerInterface;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Util\TokenUtil;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

final class ForgottenPasswordHandler implements HandlerInterface
{
    private UserRepository $userRepository;

    private MailerService $mailerService;

    private RouterInterface $router;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        MailerService $mailerService,
        RouterInterface $router,
        LoggerInterface $logger
    )
    {
        $this->userRepository = $userRepository;
        $this->mailerService  = $mailerService;
        $this->router         = $router;
        $this->logger         = $logger;
    }

    protected function findUserByEmailAddress(string $email): User
    {
        $user = $this->userRepository->findByEmailAddress($email);

        if ($user === null) {
            throw new ApiException(
                sprintf('The email address (%s) has not been recognised.', $email),
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($user->isClosed()) {
            throw new ApiException(
                'That account is closed.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $user;
    }

    protected function generateResetPasswordLink(string $token):string
    {
        return $this->router->generate(
            'reset_password',
            ['token' => $token],
            RouterInterface::ABSOLUTE_URL
        );
    }

    protected function sendForgottenPassword(string $email, string $token): void
    {
        try {
            $this->mailerService->send(
                $email,
                'Forgotten password!',
                'mails/forgotten_password.html.twig', [
                'resetPasswordURL' => $this->generateResetPasswordLink($token),
            ]);
        } catch (Throwable $e) {
            $this->logger->critical('Error occurred, please try again.',[
                'message' => $e->getMessage(),
                'email'   => $email,
            ]);

            throw new ApiException(
                'Error occurred, please try again.',
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function saveUser(User $user, string $token): User
    {
        $user->setForgottenPasswordToken($token);
        $user->setSentForgottenPassword(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        return $user;
    }

    public function handle(Object $command): User
    {
        $user = $this->findUserByEmailAddress($command->email);

        $token = TokenUtil::generate();

        $this->sendForgottenPassword($command->email, $token);
        $this->saveUser($user, $token);

        return $user;
    }
}
