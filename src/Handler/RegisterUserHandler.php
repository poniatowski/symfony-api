<?php

namespace App\Handler;

use App\DTO\User as UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;

class RegisterUserHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUserFromUserDto(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setName($userDTO->name);
        $user->setEmail($userDTO->email);
        $user->setPassword($userDTO->password);
        $user->setRegistered(new DateTime());

        return $user;
    }

    public function saveUser(UserDTO $userDTO): void
    {
        $user = $this->createUserFromUserDto($userDTO);

        $this->userRepository->saveUser($user);
    }
}