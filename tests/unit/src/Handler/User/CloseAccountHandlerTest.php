<?php namespace App\Tests\src\Handler\User;

use App\DTO\CloseAccount;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Handler\User\CloseAccountHandler;
use Codeception\Test\Unit;
use Symfony\Component\Security\Core\Security;

class CloseAccountHandlerTest extends Unit
{
    public function testCloseAccountOnSuccess(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $securityMock       = $this->createMock(Security::class);

        $userRepositoryMock->expects($this->once())
            ->method('saveUser');

        $user = new User();
        $user->setEmail('email@example.com');

        $securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);


        $closeAccountHandler = new CloseAccountHandler($userRepositoryMock, $securityMock);
        $closeAccountHandler->handle(new CloseAccount());

        $this->assertSame('email@example.com', $user->getEmail());
        $this->assertTrue($user->isClosed());
    }
}
