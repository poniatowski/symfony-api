<?php namespace App\Tests\src\Handler\User;

use App\DTO\ResetPassword as ResetPasswordDTO;
use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\User\ResetPasswordHandler;
use App\Repository\UserRepository;
use App\Util\TokenUtil;
use DateTime;
use DateInterval;
use Codeception\Test\Unit;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordHandlerTest extends Unit
{
    public function testResetPasswordOnSuccess(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $token = (new TokenUtil())->generate();

        $sentEmail = new DateTime();
        $sentEmail->add(new DateInterval('PT1H'));

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);
        $user->setForgottenPasswordToken($token);
        $user->setSentForgottenPassword($sentEmail);

        $userRepositoryMock->expects($this->once())
            ->method('findByPasswordToken')
            ->willReturn($user);
        $userRepositoryMock->expects($this->once())
            ->method('upgradePassword');
        $userRepositoryMock->expects($this->once())
            ->method('saveUser');

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn('EncodedNewPassword1');

        $resetPasswordDTO                       = new ResetPasswordDTO();
        $resetPasswordDTO->password             = 'NewPassword1';
        $resetPasswordDTO->passwordConfirmation = 'NewPassword1';
        $resetPasswordDTO->token                = $token;


        $resetPasswordHandler = new ResetPasswordHandler($userRepositoryMock, $passwordEncoderMock);
        $user = $resetPasswordHandler->handle($resetPasswordDTO);

        $this->assertSame('email@example.com', $user->getEmail());
        $this->assertNull($user->getForgottenPasswordToken());
        $this->assertNull($user->getSentForgottenPassword());
    }

    public function testNoUserExistOnFail(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $token = (new TokenUtil())->generate();

        $userRepositoryMock->expects($this->once())
            ->method('findByPasswordToken')
            ->willReturn(null);

        $resetPasswordDTO                       = new ResetPasswordDTO();
        $resetPasswordDTO->password             = 'NewPassword1';
        $resetPasswordDTO->passwordConfirmation = 'NewPassword1';
        $resetPasswordDTO->token                = $token;

        $this->expectException(ApiException::class);
        $resetPasswordHandler = new ResetPasswordHandler($userRepositoryMock, $passwordEncoderMock);
        $resetPasswordHandler->handle($resetPasswordDTO);
    }

    public function testOnExpiredTokenOnFail(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $token = (new TokenUtil())->generate();

        $sentEmail = new DateTime();
        $sentEmail->sub(new DateInterval('PT10H'));

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);
        $user->setForgottenPasswordToken($token);
        $user->setSentForgottenPassword($sentEmail);

        $userRepositoryMock->expects($this->once())
            ->method('findByPasswordToken')
            ->willReturn($user);

        $resetPasswordDTO                       = new ResetPasswordDTO();
        $resetPasswordDTO->password             = 'NewPassword1';
        $resetPasswordDTO->passwordConfirmation = 'NewPassword1';
        $resetPasswordDTO->token                = $token;


        $this->expectException(ApiException::class);
        $resetPasswordHandler = new ResetPasswordHandler($userRepositoryMock, $passwordEncoderMock);
        $resetPasswordHandler->handle($resetPasswordDTO);
    }
}
