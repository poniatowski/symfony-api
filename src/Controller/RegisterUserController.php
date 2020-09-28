<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\User as UserDTO;
use App\Handler\RegisterUserHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RegisterUserController extends AbstractController
{
    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function __invoke(
        UserDTO $userDTO,
        RegisterUserHandler $registerUserHandler,
        LoggerInterface $logger
    ): Response
    {
        try {
            $registerUserHandler->saveUser($userDTO);
        } catch (Throwable $e) {
            $logger->critical("User wasn't saved.", [
                'exception' => $e->getMessage(),
                'email'     => $userDTO->email
            ]);

            return new JsonResponse(
                [
                    'error' => 'Unable to register user. Please, try again.'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }
}
