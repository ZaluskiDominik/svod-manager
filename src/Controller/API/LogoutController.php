<?php

namespace App\Controller\API;

use App\Service\SessionUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    public function __construct(SessionUserService $sessionUserService)
    {
        $this->sessionUserService = $sessionUserService;
    }

    /** @Route("/api/{user}/logout", methods={"POST"}, requirements={"user"="customer|publisher"}) */
    public function userLogoutAction()
    {
        if ($this->sessionUserService->getUser()) {
            $this->sessionUserService->removeUser();
        }

        return new Response();
    }
}
