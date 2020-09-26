<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\ForgottenPassword;
use App\Exception\JsonValidationException;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Service\ValidateService;
use App\Utility\TokenUtility;
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
     * @Route("/api/v1/user/forgotten_password", name="forgotten_password", methods={"GET"})
     */
    public function __invoke(
        Request $request,
        UserRepository $userRepository,
        ValidateService $validator,
        MailerService $mailerService,
        RouterInterface $router
    ): Response
    {
        $email = $request->query->get('email');

        $forgottenPasswordDTO        = new ForgottenPassword();
        $forgottenPasswordDTO->email = $email;

        try {
            $validator->validate($forgottenPasswordDTO);
        } catch (JsonValidationException $errors) {
            return new JsonResponse(['error' => $errors->getErrorMessage()], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findByEmailAddress($email);

        if ($user === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('The email address (%s) has not been recognised.', $email)
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

        $token            = TokenUtility::generate();
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