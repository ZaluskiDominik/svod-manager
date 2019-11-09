<?php

namespace App\Controller\View;

use App\Entity\SessionUserEntity;
use App\Service\SessionUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    public function __construct(SessionUserService $sessionUserService)
    {
        $this->sessionUserService = $sessionUserService;
    }

    /** @Route("/dashboard", methods={"GET"}) */
    public function dashboardPageAction()
    {
        $user = $this->sessionUserService->getUser();
        if (!$user) {
            return new RedirectResponse('/login');
        }

        return $this->render(
            ($user->getUserRole() === SessionUserEntity::CUSTOMER_ROLE) ?
                'Customer/customer-dashboard.html.twig'
                :
                'Publisher/publisher-dashboard.html.twig'
        );
    }
}
