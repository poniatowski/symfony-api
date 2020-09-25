<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;

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
        $email = (new TemplatedEmail())
            ->from(new Address('hello@example.com'))
            ->to(new Address($user->getEmail()))
            ->subject('Forgotten password!')
            ->priority(Email::PRIORITY_HIGH)
            ->htmlTemplate('emails/forgotten_password.html.twig')
            ->context([
                'resetPasswordURL' => $this->getResetPasswordURL($token),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical("Forgotten password email couldn't be send.", [
                'exception' => $e,
                'email'     => $email
            ]);

            throw new Exception("Forgotten password email couldn't be send.");
        }
    }
}