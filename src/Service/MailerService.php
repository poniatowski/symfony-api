<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

class MailerService
{
    protected MailerInterface $mailer;

    protected RouterInterface $router;

    protected LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, RouterInterface $router, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->logger = $logger;
    }

    protected function getResetPasswordURL(string $token): string
    {
        return $_SERVER['DOMAIN'] .  $this->router->generate('reset_password', array('token' => $token));
    }

    public function sendForgottenPassword(User $user, string $token): void
    {
        $resetPasswordURL = sprintf('<a href="%s">restart password</a>', $this->getResetPasswordURL($token));

        $email = (new Email())
            ->from('hello@example.com')
            ->to($user->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Forgotten password!')
            ->html('<p>Click link to '. $resetPasswordURL .'.</p>');
        try {
            $this->mailer->send($email);
        } catch (Throwable $e) {
            $this->logger->critical("Forgotten password email couldn't be send.", [
                'exception' => $e,
                'email'     => $email
            ]);

            throw new Exception("Forgotten password email couldn't be send.");
        }
    }
}