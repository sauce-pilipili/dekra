<?php

namespace App\Controller;

use App\Entity\Reference;
use App\Form\ReferenceType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ReferenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 * @Route("/reference")
 */
class ReferenceController extends AbstractController
{


    /**
     * @Route("/", name="reference_index", methods={"GET","POST"})
     *
     */
    public function index(Request $request,ReferenceRepository $referenceRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->get('search');
            $references = $referenceRepository->findBySearch($data);
            return new JsonResponse([
                'content' => $this->renderView('include/_referenceContent.html.twig', compact('references')),
            ]);
        }
        return $this->render('reference/index.html.twig', [
            'references' => $referenceRepository->findAll(),
        ]);
    }
    //    **************************************************************************************************

//valide pour v2
    /**
     * @Route("/{id}/edit", name="reference_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reference $reference): Response
    {
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','La fiche référence a été modifiée avec succès !');
            return $this->redirectToRoute('reference_index');
        }

        return $this->render('reference/edit.html.twig', [
            'reference'=>$reference,
            'form' => $form->createView(),
        ]);
    }
//valide pour v2
    /**
     * @Route("/{id}", name="references_delete", methods={"POST"})
     */
    public function delete(Request $request, Reference $reference, BeneficiaireRepository $beneficiaireRepository): Response
    {   $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('delete'.$reference->getId(), $request->request->get('_token'))) {
            $beneficiaire = $beneficiaireRepository->findBy(['ReferenceEmmyDemande'=> $reference->getReference()]);
            foreach ($beneficiaire as $ben){
                $entityManager->remove($ben);
            }
            $entityManager->remove($reference);
            $entityManager->flush();
        }
        $this->addFlash('success','La fiche référence a été supprimée avec succès !');
        return $this->redirectToRoute('reference_index');
    }


    /**
     * @Route("/edit/", name="reference_ajax_edit", methods={"POST"})
     */
    public function editAjax(Request $request, ReferenceRepository $referenceRepository)
    {
        $ref=$request->request->get('input');
        $refAvalider = $referenceRepository->find($ref);
        $refAvalider->setComplet(1);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('call_center');
    }





}
