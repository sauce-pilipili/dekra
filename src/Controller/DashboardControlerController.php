<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardControlerController extends AbstractController
{
    /**
     * @Route("/dashboard/controler", name="dashboard_controler")
     */
    public function index(): Response
    {
        return $this->render('dashboard_controler/index.html.twig', [
            'controller_name' => 'DashboardControlerController',
        ]);
    }

    /**
     * @route("/dashboard/")
     */
}
