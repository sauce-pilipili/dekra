<?php

namespace App\Controller;

use App\Form\RendezVousType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ControleurRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CallCenterController extends AbstractController
{
    /**
     * @Route("/call/center", name="call_center")
     */
    public function index(BeneficiaireRepository $beneficiaireRepository): Response
    {
        $beneficiaires = $beneficiaireRepository->findAll();
        return $this->render('call_center/index.html.twig', [
            'controller_name' => 'CallCenterController',
            'beneficiaires' => $beneficiaires,
        ]);
    }

    /**
     * @Route("/call/center/rdv/{id}", name="call_center_Rdv", methods={"GET","POST"})
     */
    public function rendezVous($id, Request $request,
                               SpecialiteRepository $specialiteRepository,
                               BeneficiaireRepository $beneficiaireRepository,
                               ControleurRepository $controleurRepository,
                               MailerInterface $mailer): Response

    {
//formulaire de contact
        $form = $this->createForm(RendezVousType::class);
        $form->handleRequest($request);

        // hydratation des elemnts
        $beneficiaires = $beneficiaireRepository->show($id);
        $referenceControle = $beneficiaires->getReferenceFicheOperation();
        $specialite = $specialiteRepository->findOneBy(['referenceOperation' => $referenceControle]);
        $controlleurs = $controleurRepository->findControleurByData($beneficiaires->getDepartement(), $specialite);

        //fomulaire de prise de rendez vous
        if ($form->isSubmitted() && $form->isValid()) {
            $controlmail = $controleurRepository->find($form->get('hidden')->getViewData());
//dd($form->get('hidden')->getViewData());
            if (!$controlmail){
                $this->addFlash('danger', 'le controleur n\'a pas été sélectionné');
            }
            $date = new \DateTime($form->get('RdvDate')->getViewData());
            $date->format('d-m-Y H:i');

            $email = (new Email())
                ->from('no-reply@DekraCEE.fr')
                ->to($controlmail->getEmail())
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Un nouveau rendez-vous')
                ->html($this->renderView('include/_rendez_vous_controleur.html.twig', [
                    'controleur'=>$controlmail,
                    'date'=>$date,
                    'beneficiaire'=>$beneficiaires
                ]));
            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('call_center', [
            ]);

        }


        return $this->render('call_center/rendezvous.html.twig', [
            'form' => $form->createView(),
            'controlleurs' => $controlleurs,
            'beneficiaire' => $beneficiaires,
        ]);
    }
}
