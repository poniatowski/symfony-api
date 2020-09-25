<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Utility\TokenUtility;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class ForgottenPasswordController extends AbstractController
{
    /**
     * @Route("/api/v1/user/forgotten_password", name="forgotten_password", methods={"GET"})
     */
    public function __invoke(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        MailerService $mailerService
    ): Response
    {
        $email = $request->query->get('email');

        $constraint = new Assert\Collection([
            'email' => new Assert\Email(),
        ]);
        $violations = $validator->validate(['email' => $email], $constraint);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
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

        $token = TokenUtility::generate();

        try {
            $mailerService->sendForgottenPassword($user, $token);
        } catch (Throwable $e) {
            return new JsonResponse(
                [
                    "success" => "Error occurred, please try again."
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