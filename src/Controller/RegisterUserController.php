<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name     = $data['name'];
        $email    = $data['email'];
        $password = $data['password'];

        $this->userRepository->saveUser($name, $email, $password);

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }
}
