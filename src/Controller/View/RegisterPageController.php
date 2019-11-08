<?php

namespace App\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterPageController extends AbstractController
{
    /** @Route("/publisher-register", methods={"GET"}) */
    public function registerPublisherPageAction()
    {
        return $this->render('Publisher/publisher-register-page.html.twig', [
            'imgFilename' => 'register-publisher.jpg',
            'heading' => 'Become a publisher today!'
        ]);
    }

    /** @Route("/customer-register", methods={"GET"}) */
    public function registerCustomerPageAction()
    {
        return $this->render('Customer/customer-register-page.html.twig', [
            'imgFilename' => 'popcorn.png',
            'heading' => 'Dive into world of videos!'
        ]);
    }
}
