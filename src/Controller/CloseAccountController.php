<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CloseAccountController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/api/v1/user/close_account", name="close_account", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function closeAccount(UserRepository $userRepository): Response
    {
        $user = $this->security->getUser();

        $user->setClosed(true);
        $user->setClosedDate(new DateTime());
        $userRepository->saveUser($user);

        return $this->redirect($this->generateUrl('app_logout'));
    }
}