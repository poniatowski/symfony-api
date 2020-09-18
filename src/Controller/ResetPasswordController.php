<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController
{
    /**
     * @Route("/api/v1/forgotten_password", name="forgotten_password", methods={"GET"})
     */
    public function forgottenPassword(): Response
    {
        return new JsonResponse('success', Response::HTTP_OK);
    }

}