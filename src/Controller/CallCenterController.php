<?php

namespace App\Controller;

use App\Form\RendezVousType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ControleurRepository;
use App\Repository\ReferenceRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UserRepository;
use App\service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_CALL_CENTER')")
 */
class CallCenterController extends AbstractController
{
//    /**
//     * @Route("/call/center/", name="call_center")
//     */
//    public function index(Request $request, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
//    {
////        trouver les references Emmy
//        $refEmmy = $referenceRepository->findRef();
////            requete ajax pour affichage du pourcentage total de rdv pris
//        if ($request->isXmlHttpRequest()) {
////            recup info refEmmy par ajax
//            $emmy = $request->get('emmy');
//            $referenceAValider = $referenceRepository->findOneByReference($emmy)->getId();
//            //recup des fiches disponible sur beneficiaires
//            $reffiche = $beneficiaireRepository->findRefficheOp($emmy);
//            // recup des precarité
//            $precarite = $beneficiaireRepository->findPrecarite($emmy);
////        init du tableau de calcul final
//            $total = array();
////        requete de calcul pour chaque ref emmy/refope/precarite
//            foreach ($reffiche as $r) {
//                foreach ($precarite as $pre) {
//                    $nbBenef = $beneficiaireRepository->nombreBeneficiaireDetail($emmy, $r["referenceFicheOperation"], $pre["grandPrecairePrecaireClassique"]);
//                    $nbBenefAvecRdv = $beneficiaireRepository->nombreBeneficiaireDetailrdv($emmy, $r["referenceFicheOperation"], $pre["grandPrecairePrecaireClassique"]);
//                    if ($nbBenef == 0) {
//                        $pourcentage = 0;
//                    } else {
//                        if (($nbBenefAvecRdv * 100) / $nbBenef < 40) {
//                            $pourcentage = ($nbBenefAvecRdv * 100) / $nbBenef;
//                        } else {
//                            $pourcentage = 40;
//                        }
//                    }
//                    if ($nbBenefAvecRdv != 0 || $nbBenef != 0) {
//                        array_push($total, $pourcentage);
//                    }
//                }
//            }
////        regroupement de la somme
//            $pourcentageFinal = 0;
//            foreach ($total as $t) {
//                $pourcentageFinal += $t;
//            }
////        calcul final de la somme par ref
//            $pourcentageFinal = $pourcentageFinal / count($total);
//            return new Jsonresponse(['pourcentage' => $pourcentageFinal, 'ref' => $referenceAValider, 'emmy' => $emmy]);
//        }
//        return $this->render('call_center/index.html.twig', [
//            'reference' => $refEmmy,
//        ]);
//    }

//    /**
//     * @Route("call/center/emmy{id}", name="call_center_emmy")
//     */
//    public function CallCenterClient(Request $request, $id, BeneficiaireRepository $beneficiaireRepository, ReferenceRepository $referenceRepository, UserRepository $userRepository): Response
//    {
//
//
////        trouve les ref op presente dans le fichier avec le ref emmy
//        $refFicheOp = $beneficiaireRepository->findRefficheOp($id);
////        trouve les ref precarite presente dans le fichier avec la ref emmy
//        $refPrecarite = $beneficiaireRepository->findPrecarite($id);
//        if ($request->isXmlHttpRequest()) {
//            $emmy = $request->get('emmy');
//            $refOperation = $request->get('refOperation');
//            $precarite = $request->get('precarite');
////
//            $beneficiaire = $beneficiaireRepository->findForRdvOrdercount($emmy, $refOperation, $precarite);
//            $rdv = $beneficiaireRepository->findwhereRDV($emmy, $refOperation, $precarite);
//            return new Jsonresponse(['rdv' => $rdv, 'bene' => $beneficiaire]);
//        }
//        return $this->render('call_center/client.html.twig', [
//            'refOperation' => $refFicheOp,
//            'refPrecarite' => $refPrecarite,
//            'refemmy' => $id,
//            'ref' => $id
//        ]);
//    }

//    /**
//     * @Route("/call/center/{emmy}list{precarite}/filter{refoperation}", name="call_center_list_filter")
//     *
//     */
//    public function CLientLotFiltered(Request $request, $emmy, $precarite, $refoperation, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
//    {
//
//
//        if ($request->isXmlHttpRequest()) {
//
//            $order = $request->get('order');
//            $direction = $request->get('direction');
//            $beneficiaires = $beneficiaireRepository->findListOfBeneficiaireToCall($emmy, $precarite, $refoperation, $order, $direction);
//            return new JsonResponse([
//                'order' => $order,
//                'direction' => $direction,
//                'content' => $this->renderView('call_center/_beneficiaireContent.html.twig', compact('beneficiaires'))
//            ]);
//        }
//
//        $beneficiaires = $beneficiaireRepository->findListOfBeneficiaireToCall($emmy, $precarite, $refoperation);
//        $rdv = $beneficiaireRepository->nombreBeneficiaireDetailrdv($emmy, $refoperation, $precarite);
//
//        return $this->render('call_center/list.html.twig', [
//            'beneficiaires' => $beneficiaires,
//            'rdv' => $rdv,
//            'emmy' => $emmy,
//            'precarite' => $precarite,
//            'refOperation' => $refoperation,
//        ]);
//    }


    /**
     * @Route("/call/center/rdv/{ref}/{id}", name="call_center_Rdv", methods={"GET","POST"})
     */
    public function rendezVous($id, $ref, Request $request, EntityManagerInterface $em,
                               SpecialiteRepository $specialiteRepository,
                               BeneficiaireRepository $beneficiaireRepository,
                               ControleurRepository $controleurRepository,
                               ApiService $api,
                               MailerInterface $mailer): Response

    {



//        recherche du benef
        $beneficiaires = $beneficiaireRepository->show($id);
//        recherche de travaux similaire sur meme fiche ope
        $opeSimilaires = $beneficiaireRepository->findBy(["name" => $beneficiaires->getName(), "prenom" => $beneficiaires->getPrenom(), "adresse" => $beneficiaires->getAdresse()]);

//        AJAX de changement email
        if ($request->isXmlHttpRequest()) {
            $email = $request->get('mail');
            $beneficiaires->setEmail($email);
            $em->flush();
            $newMail = $beneficiaireRepository->find($id);
            return new Jsonresponse(['email' => $newMail->getEmail(),]);
        }
//formulaire de contact
        $form = $this->createForm(RendezVousType::class);
        $form->handleRequest($request);

        // hydratation des elements pour comparaison visuelle du call center
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
//                    $mailer->send($emailBeneficiaire);
                } catch (ExceptionInterface $exception) {
                    $this->addFlash('danger', 'l\'adresse du bénéficiaire n\'est pas valable ou le nom de domaine n\'existe pas');
                    return $this->redirectToRoute('call_center_Rdv', [
                        'id' => $beneficiaires->getId(),
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
//                $mailer->send($emailControleur);
            }
            $beneficiaires->setStatut(1);
//            mise en base de la date du RDV pour toute les entité liées

//            *************************   API   ************************************
//            envoie toutes les infos a l'api kizeo et retourne le numero id kizeo pour chacun'

            $kizeoID = $api->push($beneficiaires, $controlmail, $opeSimilaires);

            foreach ($opeSimilaires as $ope) {
                $ope->setRdv($date);
                $ope->setKizeoID($kizeoID);
            }
//            **********************************************************************
            $this->getDoctrine()->getManager()->flush();

            if ($infoClientNonAverti) {
                $this->addFlash('success', $infoClientNonAverti);
            } else {
                $this->addFlash('success', 'Votre message a bien été envoyé');
            }
            return $this->redirectToRoute('navigation_detail', [
                'ref' => $ref,
                'cdp' => $beneficiaires->getVersionCoupDePouce(),
                'ope' => $beneficiaires->getReferenceFicheOperation(),
                'preca' => $beneficiaires->getGrandPrecairePrecaireClassique()
            ]);
        }
        return $this->render('call_center/rendezvous.html.twig', [
            'form' => $form->createView(),
            'ref' => $ref,
            'controlleurs' => $controlleurs,
            'beneficiaire' => $beneficiaires,
            'operationsSimilaires' => $opeSimilaires
        ]);
    }
}
