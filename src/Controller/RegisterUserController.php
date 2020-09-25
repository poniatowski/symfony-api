<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\User as UserDTO;
use App\Handler\RegisterUserHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class RegisterUserController extends AbstractController
{
    /**
     * @Route("/api/v1/register/user", name="register_user", methods={"POST"})
     */
    public function register(
        Request $request,
        RegisterUserHandler $registerUserHandler,
        ValidatorInterface $validator,
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

        $violations = $validator->validate($userDTO);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
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
