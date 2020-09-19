<?php namespace App\Tests\src\Entity;

use App\Entity\User;
use DateTime;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testUserEntity()
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

        $this->assertEquals(99999, $user->getId());
        $this->assertEquals('username@example.com', $user->getEmail());
        $this->assertEquals('First name', $user->getFirstName());
        $this->assertEquals('Surname', $user->getSurname());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals('PASSWORD', $user->getPassword());
        $this->assertFalse(false, $user->isClosed());
        $this->assertEquals($closedDate, $user->getClosedDate());
        $this->assertEquals($registeredDate, $user->getRegistered());
        $this->assertEquals($removedDate, $user->getRemoved());
        $this->assertEquals('TOKEN', $user->getApiToken());
        $this->assertEquals('FORGOTTEN-PASSWORD-TOKEN', $user->getForgottenPasswordToken());
        $this->assertEquals($sentForgottenPassword, $user->getSentForgottenPassword());
    }
}