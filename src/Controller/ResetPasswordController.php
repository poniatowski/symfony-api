<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController
{
    /**
     * @Route("/api/v1/user/reset_password", name="reset_password", methods={"POST"})
     *
     */
    public function closeAccount(Request $request): Response
    {

    }
}