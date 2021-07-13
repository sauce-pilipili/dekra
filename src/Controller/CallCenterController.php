<?php

namespace App\Controller;

use App\Repository\ControleurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallCenterController extends AbstractController
{
    /**
     * @Route("/call/center", name="call_center")
     */
    public function index(): Response
    {
        return $this->render('call_center/index.html.twig', [
            'controller_name' => 'CallCenterController',
        ]);
    }
    /**
     * @Route("/call/center/rdv/{id}", name="call_center_Rdv", methods={"GET"})
     */
    public function rendezVous(Request $request, ControleurRepository $controleurRepository): Response
    {
        $controlleurs = $controleurRepository->findAll();

        return $this->render('call_center/rendezvous.html.twig', [
            'controlleurs' => $controlleurs,
        ]);
    }
}
