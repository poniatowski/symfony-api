<?php

namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CloseAccountController
{
    private Security $security;

    private EntityManagerInterface $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->manager  = $manager;
    }

    /**
     * @Route("/api/v1/user/close_account", name="close_account", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function closeAccount(): Response
    {
        $user = $this->security->getUser();

        $user->setClosed(true);
        $user->setClosedDate(new DateTime());
        $this->manager->persist($user);
        $this->manager->flush();

        session_destroy();

        return new JsonResponse(
            [
                'success' => 'Your account has been successfully closed'
            ],
            Response::HTTP_OK
        );
    }
}