<?php

namespace App\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginPageController extends AbstractController
{
    /** @Route("/customer-login", methods={"GET"}) */
    public function customerLoginPageAction()
    {
        return $this->render('Customer/customer-login.html.twig');
    }

    /** @Route("/publisher-login", methods={"GET"}) */
    public function publisherLoginPageAction()
    {
        return $this->render('Publisher/publisher-login.html.twig');
    }
}
