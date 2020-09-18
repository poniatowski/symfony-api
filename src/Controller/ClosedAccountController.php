<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

class ClosedAccountController
{
    private UserRepository $userRepository;

    private EntityManagerInterface $manager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager        = $manager;
    }

    /**
     * @Route("/api/v1/user/close_account", name="close_account", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function closeAccount(Request $request): Response
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

        $user->setBlocked(true);
        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            [
                'success' => 'Your account has been successfully closed'
            ],
            Response::HTTP_OK
        );
    }
}