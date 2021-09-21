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
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class CallCenterController extends AbstractController
{
    /**
     * @Route("/call/center/", name="call_center")
     */
    public function index(Request $request, BeneficiaireRepository $beneficiaireRepository): Response
    {
//        trouver les references Emmy
        $refEmmy = $beneficiaireRepository->findRef();
//            requete ajax pour affichage du pourcentage total de rdv pris
        if ($request->isXmlHttpRequest()) {
//            recup info refEmmy par ajax
            $emmy = $request->get('emmy');
            //recup des fiches disponible sur beneficiaires
            $reffiche = $beneficiaireRepository->findRefficheOp($emmy);
            // recup des precarité

            $precarite = $beneficiaireRepository->findPrecarite($emmy);

//        init du tableau de calcul final
            $total = array();
//        requet de calcul pour chaque ref emmy/refope/precarite
            foreach ($reffiche as $r) {
                foreach ($precarite as $pre) {
                    $nbBenef = $beneficiaireRepository->nombreBeneficiaireDetail($emmy, $r["referenceFicheOperation"], $pre["grandPrecairePrecaireClassique"]);
                    $nbBenefAvecRdv = $beneficiaireRepository->nombreBeneficiaireDetailrdv($emmy, $r["referenceFicheOperation"], $pre["grandPrecairePrecaireClassique"]);
                    if ($nbBenef == 0) {
                        $pourcentage = 0;
                    } else {
                        if (($nbBenefAvecRdv * 100) / $nbBenef < 40){

                            $pourcentage = ($nbBenefAvecRdv * 100) / $nbBenef;
                        }else{
                            $pourcentage= 40;
                        }
                    }
                    if ($nbBenefAvecRdv != 0 || $nbBenef != 0) {
                        array_push($total, $pourcentage);

                    }


                }
            }
//        regroupement de la somme
            $pourcentageFinal = 0;
            foreach ($total as $t) {
                $pourcentageFinal += $t;
            }
//        calcul final de la somme par ref
            $pourcentageFinal = $pourcentageFinal / count($total);
            $dump = $total;

            return new Jsonresponse(['pourcentage' => $pourcentageFinal, 'dump' => $dump]);
        }
        return $this->render('call_center/index.html.twig', [
            'controller_name' => 'CallCenterController',
            'reference' => $refEmmy,
        ]);
    }

    /**
     * @Route("call/center/emmy{id}", name="call_center_emmy")
     */
    public function CallCenterClient(Request $request, $id, BeneficiaireRepository $beneficiaireRepository, UserRepository $userRepository): Response
    {
//        trouve les ref op presente dans le fichier avec le ref emmy
        $refFicheOp = $beneficiaireRepository->findRefficheOp($id);
//        trouve les ref precarite presente dans le fichier avec la ref emmy
        $refPrecarite = $beneficiaireRepository->findPrecarite($id);

        if ($request->isXmlHttpRequest()) {
            $emmy = $request->get('emmy');
            $refOperation = $request->get('refOperation');
            $precarite = $request->get('precarite');
//
            $beneficiaire = $beneficiaireRepository->findForRdvOrdercount($emmy, $refOperation, $precarite);
            $rdv = $beneficiaireRepository->findwhereRDV($emmy, $refOperation, $precarite);
            return new Jsonresponse(['rdv' => $rdv, 'bene' => $beneficiaire]);
//            return new Jsonresponse(['emmy'=>$emmy,'refOperation'=>$refOperation, 'precarite'=>$precarite]);
        }
        $client = $userRepository->find($id);
        $beneficiaire = $beneficiaireRepository->findClientID($id);
        return $this->render('call_center/client.html.twig', [
            'refOperation' => $refFicheOp,
            'refPrecarite' => $refPrecarite,
            'refemmy' => $id,
        ]);
    }

    /**
     * @Route("/call/center/{emmy}list{precarite}/filter{refoperation}", name="call_center_list_filter")
     *
     */
    public function CLientLotFiltered(Request $request, $emmy, $precarite, $refoperation, BeneficiaireRepository $beneficiaireRepository): Response
    {
        $beneficiaires = $beneficiaireRepository->findListOfBeneficiaireToCall($emmy, $precarite, $refoperation);
        $rdv = $beneficiaireRepository->nombreBeneficiaireDetailrdv($emmy, $refoperation, $precarite);

        return $this->render('call_center/list.html.twig', [
            'beneficiaires' => $beneficiaires,
            'rdv' => $rdv,
            'emmy' => $emmy,
            'precarite' => $precarite,
            'refOperation' => $refoperation,
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
        $beneficiaire = $beneficiaireRepository->find($id);

        if ($request->isXmlHttpRequest()) {
            $email = $request->get('mail');
            $beneficiaire->setEmail($email);
            $em->flush();
            $newMail = $beneficiaireRepository->find($id);
            return new Jsonresponse(['email' => $newMail->getEmail(), ]);
        }
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
            $infoClientNonAverti = null;
            $controlmail = $controleurRepository->find($form->get('hidden')->getViewData());
            if (!$controlmail) {
                $this->addFlash('danger', 'le controleur n\'a pas été sélectionné');
            }
            $date = new \DateTime($form->get('RdvDate')->getViewData());
            $date->format('d-m-Y H:i');
            $adresse = 'no-reply-Rdv@dekra-cee.fr';
            if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $beneficiaires->getEmail()) || $beneficiaires->getEmail() != 0) {

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
                try {
                    $mailer->send($emailBeneficiaire);
                } catch (ExceptionInterface $exception) {
                    $this->addFlash('danger', 'l\'adresse du bénéficiaire n\'est pas valable ou le nom de domaine n\'existe pas');
                    return $this->redirectToRoute('call_center_Rdv', [
                        'id'=>$beneficiaires->getId(),
                        'form' => $form->createView(),
                        'controlleurs' => $controlleurs,
                        'beneficiaire' => $beneficiaires,
                    ]);
                }

            } else {
                $infoClientNonAverti = "Votre message a bien été envoyé mais uniquement le controleur a recu un mail";
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
            }




            $beneficiaires->setStatut(1);
            $beneficiaires->setRdv($date);
            $this->getDoctrine()->getManager()->flush();

            if ($infoClientNonAverti) {
                $this->addFlash('success', $infoClientNonAverti);
            } else {
                $this->addFlash('success', 'Votre message a bien été envoyé');
            }
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
