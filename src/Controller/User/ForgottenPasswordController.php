<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\CommandBus\CommandBus;
use App\DTO\ForgottenPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgottenPasswordController extends AbstractController
{
    /**
     * @Route("/api/v1/user/forgotten-password", name="forgotten_password", methods={"POST"})
     */
    public function __invoke(
        ForgottenPassword $forgottenPasswordDTO,
        CommandBus $commandBus
    ): Response
    {
        $commandBus->execute($forgottenPasswordDTO);

        return new JsonResponse(
            [
                'success' => 'Email has been successfully sent to you email address'
            ],
            Response::HTTP_OK
        );
    }
}