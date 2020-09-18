<?php

namespace App\Handler;

use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserHandler
{
    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUserFromUserDto(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $userDTO->password
        ));
        $user->setRegistered(new DateTime());
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    public function saveUser(UserDTO $userDTO): User
    {
        $user = $this->createUserFromUserDto($userDTO);

        return $this->userRepository->saveUser($user);
    }
}