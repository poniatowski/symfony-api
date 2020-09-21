<?php namespace App\Tests\src\Entity;

use App\Entity\User;
use DateTime;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    public function testUserEntity(): void
    {
        $closedDate = new DateTime('2020-01-01');
        $registeredDate = new DateTime('2018-01-01');
        $removedDate = new DateTime('2020-07-21');
        $sentForgottenPassword = new DateTime('2019-04-29');

        $user = new User();
        $user->setId(99999);
        $user->setEmail('username@example.com');
        $user->setFirstName('First name');
        $user->setSurname('Surname');
        $user->getRoles(['ROLE_USER']);
        $user->setPassword('PASSWORD');
        $user->setClosed(false);
        $user->setClosedDate($closedDate);
        $user->setRegistered($registeredDate);
        $user->setRemoved($removedDate);
        $user->setApiToken('TOKEN');
        $user->setForgottenPasswordToken('FORGOTTEN-PASSWORD-TOKEN');
        $user->setSentForgottenPassword($sentForgottenPassword);

        $this->assertSame(99999, $user->getId());
        $this->assertSame('username@example.com', $user->getEmail());
        $this->assertSame('First name', $user->getFirstName());
        $this->assertSame('Surname', $user->getSurname());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertSame('PASSWORD', $user->getPassword());
        $this->assertFalse($user->isClosed());
        $this->assertSame($closedDate, $user->getClosedDate());
        $this->assertSame($registeredDate, $user->getRegistered());
        $this->assertSame($removedDate, $user->getRemoved());
        $this->assertSame('TOKEN', $user->getApiToken());
        $this->assertSame('FORGOTTEN-PASSWORD-TOKEN', $user->getForgottenPasswordToken());
        $this->assertSame($sentForgottenPassword, $user->getSentForgottenPassword());
    }
}