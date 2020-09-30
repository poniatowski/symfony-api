<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\CommandBus;
use App\DTO\User as UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RegisterUserController extends AbstractController
{
    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function __invoke(UserDTO $userDTO, CommandBus $commandBus): Response
    {
        $commandBus->execute($userDTO);

        return new JsonResponse(
            ['success' => 'User registered!'],
            Response::HTTP_CREATED
        );
    }
}
