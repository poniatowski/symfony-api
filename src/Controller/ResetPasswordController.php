<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\DTO\ResetPassword as UserDTO;
use DateTime;
use DateInterval;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResetPasswordController
{
    /**
     * @Route("/api/v1/user/reset_password/{token}", name="reset_password", methods={"POST"})
     */
    public function closeAccount(
        string $token,
        Request $request,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $user = $userRepository->findByPasswordToken($token);

        if ($user === null) {
            return new JsonResponse(
                [
                    'error' => 'The token has been already used.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $dateLimit = $user->getSentForgottenPassword();
        $dateLimit->add(new DateInterval('PT7H'));

        if ($dateLimit < new DateTime()) {
            return new JsonResponse(
                [
                    'error' => 'The password token has expired.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = json_decode(
            $request->getContent(),
            true
        );

        $userDTO = new UserDTO();
        $userDTO->password             = $data['newPassword'] ?? null;
        $userDTO->passwordConfirmation = $data['newPasswordConfirmation'] ?? null;

        $violations = $validator->validate($userDTO);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $newEncodedPassword = $passwordEncoder->encodePassword(
            $user,
            $userDTO->password
        );
        $userRepository->upgradePassword($user, $newEncodedPassword);

        $user->setForgottenPasswordToken(null);
        $user->setSentForgottenPassword(null);
        $userRepository->saveUser($user);

        return new JsonResponse(
            [
                'success' => 'Your password has been successfully updated.'
            ],
            Response::HTTP_OK
        );
    }
}