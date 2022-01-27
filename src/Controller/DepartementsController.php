<?php

namespace App\Controller;

use App\Entity\Departements;
use App\Form\DepartementsType;
use App\Repository\DepartementsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/departements")
 *@Security("is_granted('ROLE_SUPER_ADMIN')")
 */

class DepartementsController extends AbstractController
{
    //valide pour v2
    /**
     * @Route("/", name="departements_index", methods={"GET","POST"})
     *
     */
    public function index(Request $request,DepartementsRepository $departementsRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->get('search');
            $departements = $departementsRepository->findBySearch($data);
            return new JsonResponse([
                'content' => $this->renderView('include/_departementContent.html.twig', compact('departements')),
            ]);
        }

        return $this->render('departements/index.html.twig', [
            'departements' => $departementsRepository->findAll(),
        ]);
    }
//valide pour v2
    /**
     * @Route("/new", name="departements_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $departement = new Departements();
        $form = $this->createForm(DepartementsType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departement);
            $entityManager->flush();
            $this->addFlash('success','La fiche départemement a été prise en compte avec succès !');
            return $this->redirectToRoute('departements_index');
        }

        return $this->render('departements/new.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

//valide pour v2
    /**
     * @Route("/{id}/edit", name="departements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departements $departement): Response
    {
        $form = $this->createForm(DepartementsType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','La fiche département a été modifiée avec succès !');
            return $this->redirectToRoute('departements_index');
        }

        return $this->render('departements/edit.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }
//valide pour v2
    /**
     * @Route("/{id}", name="departements_delete", methods={"POST"})
     */
    public function delete(Request $request, Departements $departement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($departement);
            $entityManager->flush();
        }
        $this->addFlash('success','La fiche département a été supprimée avec succès !');
        return $this->redirectToRoute('departements_index');
    }
}
