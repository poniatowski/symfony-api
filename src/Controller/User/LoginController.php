<?php declare(strict_types = 1);

namespace App\Controller\User;

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
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}