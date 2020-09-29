<?php declare(strict_types = 1);

namespace App\Controller\User;

use App\Handler\User\CloseAccountHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CloseAccountController extends AbstractController
{
    /**
     * @Route("/api/v1/user/close-account", name="close_account", methods={"PATCH"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(
        Security $security,
        CloseAccountHandler $closeAccountHandler
    ): Response
    {
        $closeAccountHandler->saveUser($security->getUser());

        return $this->redirect($this->generateUrl('app_logout'));
    }
}