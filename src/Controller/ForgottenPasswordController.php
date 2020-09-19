<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class ForgottenPasswordController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/v1/user/forgotten_password", name="forgotten_password", methods={"GET"})
     */
    public function forgottenPassword(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'email' => new Assert\Email(),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findByEmailAddress($data['email']);

        if ($user === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('The email address (%s) has not been recognised.', $data['email'])
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // TODO - token and email
        

        return new JsonResponse(
            [
                'success' => 'Email has been successfully sent to you email address'
            ],
            Response::HTTP_OK
        );
    }
}