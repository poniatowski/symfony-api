<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/api/v1/login", name="login", methods={"POST"})
     */
    public function login(): Response
    {
        return new JsonResponse('success', Response::HTTP_OK);
    }
}