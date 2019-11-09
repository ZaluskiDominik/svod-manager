<?php

namespace App\Controller\View;

use App\Service\SessionUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class LoginPageController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    public function __construct(SessionUserService $sessionUserService)
    {
        $this->sessionUserService = $sessionUserService;
    }

    /** @Route("/customer-login", methods={"GET"}) */
    public function customerLoginPageAction()
    {
        if ($this->sessionUserService->getUser()) {
            return new RedirectResponse('/dashboard');
        }

        return $this->render('Customer/customer-login.html.twig');
    }

    /** @Route("/publisher-login", methods={"GET"}) */
    public function publisherLoginPageAction()
    {
        if ($this->sessionUserService->getUser()) {
            return new RedirectResponse('/dashboard');
        }

        return $this->render('Publisher/publisher-login.html.twig');
    }
}
