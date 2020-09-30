<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\CommandBus;
use App\DTO\UserDetails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailsController extends AbstractController
{
    /**
     * @Route("/api/v1/user/extra-details", name="add_extra_details", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(
        UserDetails $userDetailsDTO,
        CommandBus $commandBus
    ): Response
    {
        $commandBus->execute($userDetailsDTO);

        return new JsonResponse(
            [
                'success' => 'User details successfully added.'
            ],
            Response::HTTP_OK
        );
    }
}
