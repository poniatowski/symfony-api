<?php namespace App\Tests\src\Handler;

use App\Exception\ApiException;
use App\Handler\RegisterUserHandler;
use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Tests\UnitTester;
use App\Repository\UserRepository;
use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserHandlerTest extends Unit
{
    protected UnitTester $tester;

    public function testCreateUserFromUserDtoOnSuccess(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);
        $loggerMock          = $this->createMock(LoggerInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn('hashed_password');


        $userDTO = new UserDTO();
        $userDTO->email                = 'user@domain.co.uk';
        $userDTO->password             = 'Password098';
        $userDTO->passwordConfirmation = 'Password098';

        $userHandler = new RegisterUserHandler($userRepositoryMock, $passwordEncoderMock, $loggerMock);
        $userHandler->createUserFromUserDto($userDTO);
    }

    public function testSaveUserOnSuccess(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);
        $loggerMock          = $this->createMock(LoggerInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn('hashed_password');

        $user = new User();
        $user->setPassword('hashed_password');
        $user->setEmail('user@domain.co.uk');

        $userRepositoryMock->expects($this->once())
            ->method('saveUser')
            ->willReturn($user);


        $userDTO = new UserDTO();
        $userDTO->email                = 'user@domain.co.uk';
        $userDTO->password             = 'Password098';

        $userHandler = new RegisterUserHandler($userRepositoryMock, $passwordEncoderMock, $loggerMock);
        $newUser = $userHandler->saveUser($userDTO);

        $this->assertSame('hashed_password', $newUser->getPassword());
        $this->assertSame('user@domain.co.uk', $newUser->getEmail());
        $this->assertSame(['ROLE_USER'], $newUser->getRoles());
    }

    public function testSaveUserOnException(): void
    {
        $userRepositoryMock  = $this->createMock(UserRepository::class);
        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);
        $loggerMock          = $this->createMock(LoggerInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword');

        $user = new User();
        $user->setPassword('hashed_password');
        $user->setEmail('user@domain.co.uk');

        $userDTO = new UserDTO();
        $userDTO->email                = 'user@domain.co.uk';
        $userDTO->password             = 'Password098';

        $this->expectNotice();
        $this->expectNoticeMessage('Unable to register user. Please, try again.');

        $this->expectException(ApiException::class);
        $userHandler = new RegisterUserHandler($userRepositoryMock, $passwordEncoderMock, $loggerMock);
        $userHandler->saveUser($userDTO);
    }
}
