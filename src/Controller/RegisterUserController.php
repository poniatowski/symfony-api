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


use App\Security\TokenAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Security;

class RegisterUserController extends AbstractController
{
    private RegisterUserHandler $registerUserHandler;
    private $security;

    public function __construct(RegisterUserHandler $registerUserHandler, Security $security)
    {
        $this->registerUserHandler = $registerUserHandler;
        $this->security = $security;
    }

    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function register(
        TokenAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler,
        Request $request,
        ValidatorInterface $validator
    ): Response
    {
        $data = json_decode($request->getContent(), true);

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

        $user = $this->registerUserHandler->saveUser($userDTO);

        $t = $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main'
        );

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/v1/login", name="login", methods={"POST"})
     */
    public function login(): Response
    {
        return new JsonResponse('success', Response::HTTP_OK);
    }
}
