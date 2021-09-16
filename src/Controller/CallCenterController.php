<?php

namespace App\Controller;

use App\Form\RendezVousType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ControleurRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CallCenterController extends AbstractController
{
    /**
     * @Route("/call/center/", name="call_center")
     */
    public function index(Request $request,BeneficiaireRepository $beneficiaireRepository): Response
    {
//        trouver les references Emmy
        $refEmmy = $beneficiaireRepository->findRef();
//            requete ajax pour affichage du pourcentage total de rdv pris
        if ($request->isXmlHttpRequest()){
            $emmy = $request->get('emmy');
            $pourcentage = $beneficiaireRepository->pourcentageEmmyTotal($emmy);
            return new Jsonresponse(['pourcentage'=>$pourcentage]);
        }
        return $this->render('call_center/index.html.twig', [
            'controller_name' => 'CallCenterController',
            'reference' => $refEmmy,
        ]);
    }

    /**
     * @Route("call/client{id}", name="call_center_client")
     */
    public function CallCenterClient(Request $request,$id,BeneficiaireRepository $beneficiaireRepository, UserRepository $userRepository): Response
    {

        if ($request->isXmlHttpRequest()){
            $lot = $request->get('lot');
            $category =$request->get('category');
            $statut = $request->get('statut');
            $id = $request->get('idClient');
            $beneficiaire = $beneficiaireRepository->findForRdvOrdercount($lot,$category,$statut,$id);
            $rdv = $beneficiaireRepository->findwhereRDV($lot,$category,$statut,$id);
            return new Jsonresponse(['rdv'=>$rdv,'bene'=>$beneficiaire]);
        }
        $client = $userRepository->find($id);
        $beneficiaire = $beneficiaireRepository->findClientID($id);
        return $this->render('call_center/client.html.twig', [
            'beneficiaire' => $beneficiaire,
            'client'=> $client,
        ]);
    }

    /**
     * @Route("/call/center/{lot}list{category}/{id}filter{statut}", name="call_center_list_filter")
     *
     */
    public function CLientLotFiltered(Request $request,$lot,$category,$statut,$id, BeneficiaireRepository $beneficiaireRepository): Response
    {

        $beneficiaires = $beneficiaireRepository->findForRdvOrder($lot,$category,$statut,$id);
        $rdv = $beneficiaireRepository->findwhereRDV($lot,$category,$statut,$id);

        return $this->render('call_center/list.html.twig', [
            'controller_name' => 'CallCenterController',
            'beneficiaires' => $beneficiaires,
            'clientid'=> $id,
            'rdv'=>$rdv
        ]);
    }





    /**
     * @Route("/call/center/rdv/{id}", name="call_center_Rdv", methods={"GET","POST"})
     */
    public function rendezVous($id, Request $request, EntityManagerInterface $em,
                               SpecialiteRepository $specialiteRepository,
                               BeneficiaireRepository $beneficiaireRepository,
                               ControleurRepository $controleurRepository,
                               MailerInterface $mailer): Response

    {
//formulaire de contact
        $form = $this->createForm(RendezVousType::class);
        $form->handleRequest($request);

        // hydratation des elements
        $beneficiaires = $beneficiaireRepository->show($id);

        $referenceControle = $beneficiaires->getReferenceFicheOperation();
        $specialite = $specialiteRepository->findOneBy(['referenceOperation' => $referenceControle]);
        $controlleurs = $controleurRepository->findControleurByData($beneficiaires->getDepartement(), $specialite);

        //fomulaire de prise de rendez vous
        if ($form->isSubmitted() && $form->isValid()) {

            $controlmail = $controleurRepository->find($form->get('hidden')->getViewData());
            if (!$controlmail) {
                $this->addFlash('danger', 'le controleur n\'a pas été sélectionné');
            }
            $date = new \DateTime($form->get('RdvDate')->getViewData());
            $date->format('d-m-Y H:i');
            $adresse = 'no-reply-Rdv@dekra-cee.fr';
            $emailControleur = (new Email())
                ->from($adresse)
                ->to($controlmail->getEmail())
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Un nouveau rendez-vous')
                ->html($this->renderView('include/_rendez_vous_controleur.html.twig', [
                    'controleur' => $controlmail,
                    'date' => $date,
                    'beneficiaire' => $beneficiaires
                ]));
            $mailer->send($emailControleur);
            $emailBeneficiaire = (new Email())
                ->from($adresse)
                ->to($beneficiaires->getEmail())
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Un nouveau rendez-vous')
                ->html($this->renderView('include/_rendez_vous_beneficiaire.html.twig', [
                    'controleur' => $controlmail,
                    'date' => $date,
                    'beneficiaire' => $beneficiaires
                ]));
            $mailer->send($emailBeneficiaire);
            $beneficiaires->setStatut(1);
            $beneficiaires->setRdv($date);
            $this->getDoctrine()->getManager()->flush();
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
