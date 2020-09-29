<?php namespace App\Tests\src\Handler\User;

use App\DTO\ForgottenPassword;
use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\User\ForgottenPasswordHandler;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Util\TokenUtil;
use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

class ForgottenPasswordHandlerTest extends Unit
{
    public function testSendForgottenPasswordOnSuccess(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $mailerServiceMock  = $this->createMock(MailerService::class);
        $tokenUtilMock      = $this->createMock(TokenUtil::class);
        $routerMock         = $this->createMock(RouterInterface::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);
        $userRepositoryMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn($user);
        $userRepositoryMock->expects($this->once())
            ->method('saveUser');

        $mailerServiceMock->expects($this->once())
            ->method('send');

        $token = (new TokenUtil())->generate();
        $tokenUtilMock->expects($this->once())
            ->method('generate')
            ->willReturn($token);

        $routerMock->expects($this->once())
            ->method('generate')
            ->willReturn('/api/v1/user/reset-password/'. $token);

        $forgottenPassword        = new ForgottenPassword();
        $forgottenPassword->email = 'email@example.com';


        $forgottenPasswordHandler = new ForgottenPasswordHandler($userRepositoryMock, $mailerServiceMock, $tokenUtilMock, $routerMock, $loggerMock);
        $user = $forgottenPasswordHandler->handle($forgottenPassword);

        $this->assertSame('email@example.com', $user->getEmail());
        $this->assertFalse($user->isClosed());
        $this->assertSame($token, $user->getForgottenPasswordToken());
        $this->assertNotNull($user->getSentForgottenPassword());
    }

    public function testSendForgottenPasswordNonExistUserOnFail(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $mailerServiceMock  = $this->createMock(MailerService::class);
        $tokenUtilMock      = $this->createMock(TokenUtil::class);
        $routerMock         = $this->createMock(RouterInterface::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $userRepositoryMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn(null);

        $forgottenPassword        = new ForgottenPassword();
        $forgottenPassword->email = 'email@example.com';


        $this->expectException(ApiException::class);
        $forgottenPasswordHandler = new ForgottenPasswordHandler($userRepositoryMock, $mailerServiceMock, $tokenUtilMock, $routerMock, $loggerMock);
        $forgottenPasswordHandler->handle($forgottenPassword);
    }

    public function testSendForgottenPasswordOnClosedAccountOnFail(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $mailerServiceMock  = $this->createMock(MailerService::class);
        $tokenUtilMock      = $this->createMock(TokenUtil::class);
        $routerMock         = $this->createMock(RouterInterface::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(true);
        $userRepositoryMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn($user);

        $forgottenPassword        = new ForgottenPassword();
        $forgottenPassword->email = 'email@example.com';


        $this->expectException(ApiException::class);
        $forgottenPasswordHandler = new ForgottenPasswordHandler($userRepositoryMock, $mailerServiceMock, $tokenUtilMock, $routerMock, $loggerMock);
        $forgottenPasswordHandler->handle($forgottenPassword);
    }

    public function testSendForgottenPasswordOnSendEmailOnFail(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $mailerServiceMock  = $this->createMock(MailerService::class);
        $tokenUtilMock      = $this->createMock(TokenUtil::class);
        $routerMock         = $this->createMock(RouterInterface::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);
        $userRepositoryMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn($user);

        $token = (new TokenUtil())->generate();
        $tokenUtilMock->expects($this->once())
            ->method('generate')
            ->willReturn($token);

        $forgottenPassword        = new ForgottenPassword();
        $forgottenPassword->email = 'email@example.com';

        $this->expectException(ApiException::class);
        $forgottenPasswordHandler = new ForgottenPasswordHandler($userRepositoryMock, $mailerServiceMock, $tokenUtilMock, $routerMock, $loggerMock);
        $forgottenPasswordHandler->handle($forgottenPassword);
    }
}