<?php

namespace App\Controller;

use App\Repository\BeneficiaireRepository;
use App\Repository\ReferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 *  * @Route("/navigation")
 */
class NavigationClientController extends AbstractController
{
    /**
     * toute les referecnce emmy du client connecté
     * @Route("/reference", name="navigation_reference")
     */
    public function index(ReferenceRepository $referenceRepository): Response
    {
        //reference du client connecté
        $reference = $referenceRepository->findBy(['client' => $this->getUser()]);
        return $this->render('navigation_client/index.html.twig', [
            'reference' => $reference,
        ]);
    }

    /**
     * toute les referecnce emmy du client connecté
     * @Route("/reference/call", name="navigation_call")
     */
    public function callCenter(ReferenceRepository $referenceRepository): Response
    {
        //reference du client connecté
        $reference = $referenceRepository->findAll();
        return $this->render('navigation_client/index.html.twig', [
            'reference' => $reference,
        ]);
    }

    /**
     * toute les reference emmy du client connecté par la voie admin
     * @Route("/reference/admin/{id}", name="navigation_admin")
     */
    public function indexAdmin($id,Request $request,ReferenceRepository $referenceRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            $reference = $referenceRepository->find($request->get('reference'));
            $reference->setIdKizeoForm($request->get('kizeo'));
            $this->getDoctrine()->getManager()->flush();

            return new Jsonresponse(['kizeoId' => $reference->getIdKizeoForm(),
                ]);
        }

        //reference du client connecté
        $reference = $referenceRepository->findBy(['client' => $id]);
        return $this->render('navigation_client/index.html.twig', [
            'reference' => $reference,
            'idClient'=>$id,

        ]);
    }



    /**
     * tout les coup de pouce ou autre par ref
     * @Route("/emmy/{id}", name="navigation_emmy")
     */
    public function emmy($id, Request $request, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
    {
        $emmy = $referenceRepository->find($id)->getReference();
        $client = $referenceRepository->find($id)->getClient()->getId();
        $coupDePouce = $beneficiaireRepository->findCoupDePouce($emmy);
        if ($request->isXmlHttpRequest()) {
            $result = $beneficiaireRepository->nombreSatisfaisant($emmy, $request->get('cdp'));
            $content = $beneficiaireRepository->nombreDossierDepose($emmy, $request->get('cdp'));
            return new JsonResponse(['content' => $content, 'result' => $result]);
        }
        return $this->render('navigation_client/emmy.html.twig', [
            'refemmyDemande' => $id,
            'reference' => $emmy,
            'coupDePouce' => $coupDePouce,
            'client' => $client
        ]);
    }

    /**
     * si il y a un cdp 2019 2020 2021 ou 164
     * @Route("/cdp/{ref}/{cdp}", name="navigation_cdp")
     */
    public function cdp($ref, $cdp, Request $request, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
    {

        $idRef = $ref;
        $ref = $referenceRepository->find($ref)->getReference();
        $result = $beneficiaireRepository->findByCDPDossier($ref, $cdp);
        if ($request->isXmlHttpRequest()) {
            $result = $beneficiaireRepository->nombreSatisfaisant($ref, $request->get('cdp'), $request->get('fiche'));
            $content = $beneficiaireRepository->nombreDossierDepose($ref, $request->get('cdp'), $request->get('fiche'));
            return new JsonResponse(['content' => $content, 'result' => $result, 'pouce' => $request->get('pouce'), 'fiche' => $request->get('fiche')]);
        }
        return $this->render('navigation_client/dossier.html.twig', [
            'ref' => $ref,
            'cdp' => $cdp,
            'idRef' => $idRef,
            'result' => $result,
        ]);
    }

    /**
     * toute les precarité correspondantes avec les cdp
     * @Route("/precarite/{ref}/{cdp}-{ope}", name="navigation_precarite")
     */
    public function precarite($cdp, $ref, $ope, Request $request, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
    {

        $idRef = $ref;
        $ref = $referenceRepository->find($ref)->getReference();
        $result = $beneficiaireRepository->findByPrecaritéByCDPDossier($ref, $cdp, $ope);

        if ($request->isXmlHttpRequest()) {
            $result = $beneficiaireRepository->nombreSatisfaisant($ref, $request->get('cdp'), $request->get('fiche'), $request->get('preca'));
            $content = $beneficiaireRepository->nombreDossierDepose($ref, $request->get('cdp'), $request->get('fiche'), $request->get('preca'));
            return new JsonResponse(['content' => $content, 'result' => $result]);
        }

        return $this->render('navigation_client/precarite.html.twig', [
            'ref' => $ref,
            'cdp' => $cdp,
            'ope' => $ope,
            'idRef' => $idRef,
            'result' => $result,
        ]);
    }

    /**
     * detail des benef derniere page de la navigation
     * @Route("/detail/{ref}/{cdp}/{ope}/{preca}", name="navigation_detail")
     */
    public function detail($ref, $cdp, $ope, $preca, Request $request, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository): Response
    {
        $idRef = $ref;
        $ref = $referenceRepository->find($ref)->getReference();
        $result = $beneficiaireRepository->findlistBycriteria($ref, $cdp, $ope, $preca);
        if ($request->isXmlHttpRequest()&&$request->get('order') || $request->isXmlHttpRequest()&&$request->get('direction')) {
            $order = $request->get('order');
            $direction = $request->get('direction');
            $result = $beneficiaireRepository->findListOfBeneficiaireToCall($ref, $cdp,$preca, $ope, $order, $direction);
            return new JsonResponse([
                'order' => $order,
                'direction' => $direction,
                'content' => $this->renderView('call_center/_beneficiaireContent.html.twig', compact('result'))
            ]);
        }
        if ($request->isXmlHttpRequest()&& $request->get('search')) {
            $result = $beneficiaireRepository->findListOfBeneficiaireSearch($ref, $cdp,$preca, $ope,$request->get('search'));
            return new JsonResponse([
                'content' => $this->renderView('call_center/_beneficiaireContent.html.twig', compact('result'))
            ]);
        }


        if ($request->isXmlHttpRequest()) {
            $result = $beneficiaireRepository->nombreSatisfaisantCDP(
                $ref,
                $request->get('cdp'),
                $request->get('fiche'),
                substr($request->get('fiche'), -3, 3),
                $request->get('preca'));

            $content = $beneficiaireRepository->nombreDossierDeposeavecCdp($ref, $request->get('pouce'), $request->get('fiche'), $request->get('preca'));
            return new JsonResponse(['content' => $content, 'result' => $result]);
        }
        return $this->render('navigation_client/detail.html.twig', [
            'result' => $result,
            'idRef' => $idRef,
            'ref' => $ref,
            'cdp' => $cdp,
            'ope' => $ope,
            'preca' => $preca
        ]);
    }


    /**
     * detail d'un beneficiaire avec retour
     * @Route("/show{id}", name="navigation_show", methods={"GET"})
     */
    public function show($id, ReferenceRepository $referenceRepository, BeneficiaireRepository $beneficiaireRepository)
    {
        $beneficiaire = $beneficiaireRepository->show($id);
        $ref = $referenceRepository->findOneBy(['reference' =>$beneficiaire->getReferenceEmmyDemande()]);
        $ref = $ref->getId();
        return $this->render('navigation_client/show.html.twig', [
            'beneficiaire' => $beneficiaire,
            'ref' => $ref,
            'cdp' => $beneficiaire->getVersionCoupDePouce(),
            'ope' => $beneficiaire->getReferenceFicheOperation(),
            'preca' => $beneficiaire->getGrandPrecairePrecaireClassique(),
        ]);
    }


}
