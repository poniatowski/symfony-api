<?php namespace App\Tests\src\Handler\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Handler\User\CloseAccountHandler;
use Codeception\Test\Unit;

class CloseAccountHandlerTest extends Unit
{
    public function testCloseAccountOnSuccess(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock->expects($this->once())
            ->method('saveUser');


        $user = new User();
        $user->setEmail('email@example.com');

        $closeAccountHandler = new CloseAccountHandler($userRepositoryMock);
        $closeAccountHandler->handle($user);

        $this->assertSame('email@example.com', $user->getEmail());
        $this->assertTrue($user->isClosed());
    }
}
