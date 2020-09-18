<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController
{
    /**
     * @Route("/api/v1/user/reset_password", name="reset_password", methods={"POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function closeAccount(Request $request): Response
    {

    }
}