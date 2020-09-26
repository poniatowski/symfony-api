<?php declare(strict_types = 1);

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    protected MailerInterface $mailer;

    protected LoggerInterface $logger;

    protected ?string $senderAddress;

    protected ?string $senderName;

    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        string $senderAddress = null,
        string $senderName = null
    )
    {
        $this->mailer        = $mailer;
        $this->logger        = $logger;
        $this->senderAddress = $senderAddress;
        $this->senderName    = $senderName;
    }

    public function send(string $recipient, string $subject, string $templatePath, array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->senderAddress, $this->senderName))
            ->to($recipient)
            ->subject($subject)
            ->htmlTemplate($templatePath);

        if ($context) {
            $email->context($context);
        }

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
