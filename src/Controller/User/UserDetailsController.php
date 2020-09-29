<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\DTO\User;
use App\Handler\User\UserDetailsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserDetailsController extends AbstractController
{
    /**
     * @Route("/api/v1/user/extra-details", name="add_extra_details", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(
        User $userDetailsDTO,
        Security $security,
        UserDetailsHandler $userDetailsHandler
    ): Response
    {
        $userDetailsHandler->saveUser($security->getUser(), $userDetailsDTO);

        return new JsonResponse(
            [
                'success' => 'User details successfully added.'
            ],
            Response::HTTP_OK
        );
    }
}
