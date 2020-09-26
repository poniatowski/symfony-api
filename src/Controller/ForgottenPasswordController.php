<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\ForgottenPassword;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Util\TokenUtil;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

class ForgottenPasswordController extends AbstractController
{
    /**
     * @Route("/api/v1/user/forgotten-password", name="forgotten_password", methods={"POST"})
     */
    public function __invoke(
        ForgottenPassword $forgottenPasswordDTO,
        Request $request,
        UserRepository $userRepository,
        MailerService $mailerService,
        RouterInterface $router
    ): Response
    {
        $user = $userRepository->findByEmailAddress($forgottenPasswordDTO->email);

        if ($user === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('The email address (%s) has not been recognised.', $forgottenPasswordDTO->email)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($user->isClosed()) {
            return new JsonResponse(
                [
                    'error' => 'That account is closed.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $token            = TokenUtil::generate();
        $resetPasswordURL = $router->generate('reset_password', ['token' => $token], RouterInterface::ABSOLUTE_URL);

        try {
            $mailerService->send($user->getEmail(), 'Forgotten password!', 'mails/forgotten_password.html.twig', [
                'resetPasswordURL'  => $resetPasswordURL,
            ]);
        } catch (Throwable $e) {
            return new JsonResponse(
                [
                    "error" => "Error occurred, please try again."
                ],
                Response::HTTP_OK
            );
        }

        $user->setForgottenPasswordToken($token);
        $user->setSentForgottenPassword(new DateTime());
        $userRepository->saveUser($user);

        return new JsonResponse(
            [
                'success' => 'Email has been successfully sent to you email address'
            ],
            Response::HTTP_OK
        );
    }
}