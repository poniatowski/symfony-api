<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

class ForgottenPasswordController extends AbstractController
{
    /**
     * @Route("/api/v1/forgotten_password", name="forgotten_password", methods={"GET"})
     */
    public function forgottenPassword(Reguest $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'email' => new Assert\Length(array('min' => 3, 'max' => 255)),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }



        return new JsonResponse('success', Response::HTTP_OK);
    }
}