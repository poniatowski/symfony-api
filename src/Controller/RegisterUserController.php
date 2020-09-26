<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\User as UserDTO;
use App\Exception\JsonValidationException;
use App\Handler\RegisterUserHandler;
use App\Service\ValidateService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RegisterUserController extends AbstractController
{
    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function __invoke(
        Request $request,
        RegisterUserHandler $registerUserHandler,
        ValidateService $validator,
        LoggerInterface $logger
    ): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $userDTO = new UserDTO();
        $userDTO->email                = $data['email'] ?? null;
        $userDTO->password             = $data['password'] ?? null;
        $userDTO->passwordConfirmation = $data['passwordConfirmation'] ?? null;

        try {
            $validator->validate($userDTO);
        } catch (JsonValidationException $errors) {
            return new JsonResponse(['error' => $errors->getErrorMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $registerUserHandler->saveUser($userDTO);
        } catch (Throwable $e) {
            $logger->critical("User wasn't saved.", [
                'exception' => $e,
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
