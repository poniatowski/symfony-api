<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    private EntityManagerInterface $manager;

    public function __construct(
        Security $security,
        EntityManagerInterface $manager
    )
    {
        $this->security = $security;
        $this->manager  = $manager;
    }

    /**
     * @Route("/api/v1/user/extra_details", name="add_extra_details", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function addExtraDetails(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'firstname' => new Assert\Length(array('min' => 3, 'max' => 255)),
            'surname' => new Assert\Length(array('min' => 3, 'max' => 255)),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->security->getUser();
            $user->setFirstName(ucwords($data['firstname']));
            $user->setSurname(ucwords($data['surname']));
            $this->manager->persist($user);
            $this->manager->flush();
        } catch (Throwable $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse('success', Response::HTTP_OK);
    }
}
