<?php

namespace App\Controller;

use App\Entity\Departements;
use App\Form\DepartementsType;
use App\Repository\DepartementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/departements")
 */
class DepartementsController extends AbstractController
{
    /**
     * @Route("/", name="departements_index", methods={"GET"})
     */
    public function index(DepartementsRepository $departementsRepository): Response
    {
        return $this->render('departements/index.html.twig', [
            'departements' => $departementsRepository->findAll(),
        ]);
    }

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
            $this->addFlash('success','le départemement a été pris en compte');
            return $this->redirectToRoute('departements_index');
        }

        return $this->render('departements/new.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="departements_show", methods={"GET"})
     */
    public function show(Departements $departement): Response
    {
        return $this->render('departements/show.html.twig', [
            'departement' => $departement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="departements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departements $departement): Response
    {
        $form = $this->createForm(DepartementsType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','le départemement a été modifié');
            return $this->redirectToRoute('departements_index');
        }

        return $this->render('departements/edit.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

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
        $this->addFlash('success','le départemement a été supprimé');
        return $this->redirectToRoute('departements_index');
    }
}
