<?php

namespace App\Controller;

use App\DTO\User as UserDTO;
use App\Handler\RegisterUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserController extends AbstractController
{
    private RegisterUserHandler $registerUserHandler;

    public function __construct(RegisterUserHandler $registerUserHandler)
    {
        $this->registerUserHandler = $registerUserHandler;
    }

    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function register(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $userDTO = new UserDTO();
        $userDTO->email                = $data['email'] ?? null;
        $userDTO->password             = $data['password'] ?? null;
        $userDTO->passwordConfirmation = $data['passwordConfirmation'] ?? null;

        $errors = [];
        if($userDTO->password !== $userDTO->passwordConfirmation)
        {
            $errors[] = "Password does not match the password confirmation.";
        }
        if(strlen($userDTO->password) < 6)
        {
            $errors[] = "Password should be at least 6 characters.";
        }

        $violations = $validator->validate($userDTO);
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->registerUserHandler->saveUser($userDTO);

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }
}
