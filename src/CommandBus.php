<?php declare(strict_types = 1);

namespace App;

use App\DTO\ForgottenPassword;
use App\DTO\ResetPassword;
use App\DTO\User;
use App\DTO\UserDetails;
use App\Handler\User\CloseAccountHandler;
use App\Handler\User\ForgottenPasswordHandler;
use App\Handler\User\RegisterUserHandler;
use App\Handler\User\ResetPasswordHandler;
use App\Handler\User\UserDetailsHandler;
use App\Util\CommandInterface as Command;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use App\DTO\CloseAccount;

class CommandBus implements ServiceSubscriberInterface, Command
{
    private ContainerInterface $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices(): array
    {
        return [
            CloseAccount::class      => CloseAccountHandler::class,
            ForgottenPassword::class => ForgottenPasswordHandler::class,
            User::class              => RegisterUserHandler::class,
            ResetPassword::class     => ResetPasswordHandler::class,
            UserDetails::class       => UserDetailsHandler::class,
        ];
    }

    public function execute(Command $command)
    {
        $commandClass = get_class($command);

        if ($this->locator->has($commandClass)) {
            $handler = $this->locator->get($commandClass);

            return $handler->handle($command);
        }
    }
}