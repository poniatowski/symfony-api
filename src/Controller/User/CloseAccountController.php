<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\CommandBus;
use App\DTO\CloseAccount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloseAccountController extends AbstractController
{
    /**
     * @Route("/api/v1/user/close-account", name="close_account", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(
        CommandBus $commandBus
    ): Response
    {
        $commandBus->execute(new CloseAccount());

        return $this->redirect($this->generateUrl('app_logout'));
    }
}