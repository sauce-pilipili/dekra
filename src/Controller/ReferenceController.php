<?php

namespace App\Controller;

use App\Entity\Reference;
use App\Form\ReferenceType;
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
     * @Route("/", name="reference_index", methods={"GET"})
     */
    public function index(ReferenceRepository $referenceRepository): Response
    {
        return $this->render('reference/index.html.twig', [
            'references' => $referenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reference_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reference = new Reference();
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reference);
            $entityManager->flush();

            return $this->redirectToRoute('reference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reference/new.html.twig', [
            'reference' => $reference,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="reference_show", methods={"GET"})
     */
    public function show(Reference $reference): Response
    {
        return $this->render('reference/show.html.twig', [
            'reference' => $reference,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reference_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reference $reference): Response
    {
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reference/edit.html.twig', [
            'reference' => $reference,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="reference_delete", methods={"POST"})
     */
    public function delete(Request $request, Reference $reference): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reference->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reference_index', [], Response::HTTP_SEE_OTHER);
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
