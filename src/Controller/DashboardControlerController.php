<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardControlerController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 */

class DashboardControlerController extends AbstractController
{
    /**
     * @Route("/dashboard/controler", name="dashboard_controler")
     */
    public function index(): Response
    {

        return $this->render('dashboard_controler/index.html.twig');
    }



}
