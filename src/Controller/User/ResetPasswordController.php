<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\Handler\User\ResetPasswordHandler;
use App\DTO\ResetPassword as ResetPasswordDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController
{
    /**
     * @Route("/api/v1/user/reset-password/{token}", name="reset_password", methods={"POST"})
     */
    public function __invoke(
        string $token,
        ResetPasswordDTO $resetPasswordDTO,
        ResetPasswordHandler $resetPasswordHandler
    ): Response
    {
        $resetPasswordHandler->resetPassword($token, $resetPasswordDTO->password);

        return new JsonResponse(
            [
                'success' => 'Your password has been successfully updated.'
            ],
            Response::HTTP_OK
        );
    }
}