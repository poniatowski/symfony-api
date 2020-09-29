<?php namespace App\Tests\src\Handler\User;

use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Exception\ApiException;
use App\Handler\User\UserDetailsHandler;
use App\Repository\UserRepository;
use Codeception\Test\Unit;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class UserDetailsHandlerTest extends Unit
{
    public function testUpdateUserDetailsOnSuccess(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $securityMock       = $this->createMock(Security::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);

        $securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepositoryMock->expects($this->once())
            ->method('saveUser');

        $userDetailsDTO            = new UserDTO();
        $userDetailsDTO->firstname = 'Firstname';
        $userDetailsDTO->surname   = 'Surname';


        $userDetailsHandler = new UserDetailsHandler($userRepositoryMock, $securityMock, $loggerMock);
        $user = $userDetailsHandler->handle($userDetailsDTO);

        $this->assertSame('email@example.com', $user->getEmail());
        $this->assertFalse($user->isClosed());
        $this->assertSame('Firstname', $user->getFirstName());
        $this->assertSame('Surname', $user->getSurname());
    }

    public function testSaveUserOnFail(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $securityMock       = $this->createMock(Security::class);
        $loggerMock         = $this->createMock(LoggerInterface::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setClosed(false);

        $securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepositoryMock->expects($this->once())
            ->method('saveUser')
            ->will($this->throwException(new Exception('Fatal error')));

        $loggerMock->expects($this->once())
            ->method('critical');

        $userDetailsDTO            = new UserDTO();
        $userDetailsDTO->firstname = 'Firstname';
        $userDetailsDTO->surname   = 'Surname';


        $this->expectException(ApiException::class);
        $userDetailsHandler = new UserDetailsHandler($userRepositoryMock, $securityMock, $loggerMock);
        $userDetailsHandler->handle($userDetailsDTO);
    }
}