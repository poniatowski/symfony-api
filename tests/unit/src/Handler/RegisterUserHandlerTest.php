<?php namespace App\Tests\src\Handler;

use App\Handler\RegisterUserHandler;
use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Tests\UnitTester;
use App\Repository\UserRepository;
use Codeception\Test\Unit;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserHandlerTest extends Unit
{
    protected UnitTester $tester;

    public function testCreateUserFromUserDto(): void
    {
        $userRepository  = $this->createMock(UserRepository::class);
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn('hashed_password');


        $userDTO = new UserDTO();
        $userDTO->email                = 'user@domain.co.uk';
        $userDTO->password             = 'Password098';
        $userDTO->passwordConfirmation = 'Password098';

        $userRepository = new RegisterUserHandler($userRepository, $passwordEncoder);
        $userRepository->createUserFromUserDto($userDTO);
    }

    public function testSaveUser(): void
    {
        $userRepository  = $this->createMock(UserRepository::class);
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn('hashed_password');

        $user = new User();
        $user->setPassword('hashed_password');
        $user->setEmail('user@domain.co.uk');

        $userRepository->expects($this->once())
            ->method('saveUser')
            ->willReturn($user);


        $userDTO = new UserDTO();
        $userDTO->email                = 'user@domain.co.uk';
        $userDTO->password             = 'Password098';

        $userRepository = new RegisterUserHandler($userRepository, $passwordEncoder);
        $newUser = $userRepository->saveUser($userDTO);

        $this->assertSame('hashed_password', $newUser->getPassword());
        $this->assertSame('user@domain.co.uk', $newUser->getEmail());
        $this->assertSame(['ROLE_USER'], $newUser->getRoles());
    }
}
