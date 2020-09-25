<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class UserDetailsController extends AbstractController
{
    /**
     * @Route("/api/v1/user/extra_details", name="add_extra_details", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function addExtraDetails(
        Request $request,
        ValidatorInterface $validator,
        Security $security,
        UserRepository $userRepository,
        LoggerInterface $logger
    ): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

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
            $user = $security->getUser();
            $user->setFirstName(ucwords($data['firstname']));
            $user->setSurname(ucwords($data['surname']));
            $userRepository->saveUser($user);
        } catch (Throwable $e) {
            $logger->critical("We can't update user.", [
                'exception' => $e,
                'email'     => $user->getUsername()
            ]);
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(
            [
                'success' => 'User details successfully added.'
            ],
            Response::HTTP_OK
        );
    }
}
