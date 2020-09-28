<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class UserDetailsController extends AbstractController
{
    /**
     * @Route("/api/v1/user/extra-details", name="add_extra_details", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(
        User $userDetailsDTO,
        Request $request,
        Security $security,
        UserRepository $userRepository,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ): Response
    {
        try {
            $user = $security->getUser();
            $user->setFirstName(ucwords($userDetailsDTO->firstname));
            $user->setSurname(ucwords($userDetailsDTO->surname));
            $userRepository->saveUser($user);
        } catch (Throwable $e) {
            $logger->critical("We can't update user.", [
                'exception' => $e->getMessage(),
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
