<?php

namespace App\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /** @Route("/dashboard", methods={"GET"}) */
    public function dashboardPageAction()
    {
        return $this->render('dashboard.html.twig');
    }
}
