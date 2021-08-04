<?php

namespace App\Controller;

use App\Repository\BeneficiaireRepository;
use App\Repository\ControleurRepository;
use App\Repository\DepartementsRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallCenterController extends AbstractController
{
    /**
     * @Route("/call/center", name="call_center")
     */
    public function index(BeneficiaireRepository $beneficiaireRepository): Response
    {
        $beneficiaires= $beneficiaireRepository->findAll();
        return $this->render('call_center/index.html.twig', [
            'controller_name' => 'CallCenterController',
            'beneficiaires'=> $beneficiaires,
        ]);
    }
    /**
     * @Route("/call/center/rdv/{id}", name="call_center_Rdv", methods={"GET"})
     */
    public function rendezVous($id,Request $request,
                               SpecialiteRepository $specialiteRepository,
                               BeneficiaireRepository $beneficiaireRepository,
                               DepartementsRepository $departementsRepository,
                               ControleurRepository $controleurRepository): Response
    {
        $beneficiaires = $beneficiaireRepository->show($id);
        $referenceControle = $beneficiaires->getReferenceFicheOperation();
        $specialite = $specialiteRepository->findOneBy(['referenceOperation'=> $referenceControle]);
        $controlleurs = $controleurRepository->findControleurByData($beneficiaires->getDepartement(),$specialite);

        return $this->render('call_center/rendezvous.html.twig', [
            'controlleurs' => $controlleurs,
            'beneficiaire'=> $beneficiaires,
        ]);
    }
}
