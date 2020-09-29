<?php declare(strict_types = 1);

namespace App\Controller\User;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/api/v1/logout", name="app_logout", methods={"GET"})
     *
     * @throws Exception
     */
    public function logout(): Response
    {
        throw new Exception("We will never arrive here");
    }

    /**
     * @Route("/logout_message", name="logout_message")
     */
    public function logoutMessage()
    {
        return new JsonResponse(["success" => "You've been logged out."], Response::HTTP_OK);
    }
}