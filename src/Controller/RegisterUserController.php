<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\User as UserDTO;
use App\Handler\RegisterUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RegisterUserController extends AbstractController
{
    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function __invoke(UserDTO $userDTO, RegisterUserHandler $registerUserHandler): Response
    {
        $registerUserHandler->saveUser($userDTO);

        return new JsonResponse(
            ['success' => 'User registered!'],
            Response::HTTP_CREATED
        );
    }
}
