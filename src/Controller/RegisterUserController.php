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
        $data = json_decode($request->getContent(), true);

        $user = new UserDTO();
        $user->name     = $data['name'] ?? null;
        $user->email    = $data['email'] ?? null;
        $user->password = $data['password'] ?? null;

        $violations = $validator->validate($user);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->registerUserHandler->saveUser($user);

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }
}
