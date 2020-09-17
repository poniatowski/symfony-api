<?php

namespace App\Controller;

use App\DTO\User as UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

class UserDetailsController extends AbstractController
{
    private Security $security;

     public function __construct(Security $security)
     {
         $this->security = $security;
     }

    /**
     * @Route("/api/v1/user/extra_details", name="add_extra_details", methods={"PATCH"})
     */
    public function addExtraDetails(Request $request): Response
    {
        if ($this->security->getUser()) {
            return false;
        }

        $t = $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'firstname' => new Assert\Length(array('min' => 1, 'max' => 255)),
            'surname' => new Assert\Length(array('min' => 1, 'max' => 255)),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            // $userManager->updateUser($user, true);
        } catch (Throwable $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse('success', Response::HTTP_OK);
    }

    /**
     * @Route("/api/v1/user/close_account", name="close_account", methods={"PATCH"})
     */
    public function closeAccount(): Response
    {
        return new JsonResponse('success', Response::HTTP_OK);
    }
}
