<?php

namespace App\Controller\View;

use App\Service\SessionUserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /** @var SessionUserService */
    private $sessionUserService;

    public function __construct(SessionUserService $sessionUserService)
    {
        $this->sessionUserService = $sessionUserService;
    }

    /** @Route("/", methods={"GET"}) */
    public function indexAction()
    {
        return new RedirectResponse(
            ($this->sessionUserService->getUser()) ? '/dashboard' : '/login'
        );
    }
}
