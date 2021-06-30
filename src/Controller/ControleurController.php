<?php

namespace App\Controller;

use App\Entity\Controleur;
use App\Form\ControleurType;
use App\Repository\ControleurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/controleur")
 */
class ControleurController extends AbstractController
{
    /**
     * @Route("/index", name="controleur_index", methods={"GET"})
     */
    public function index(ControleurRepository $controleurRepository): Response
    {
        return $this->render('controleur/index.html.twig', [
            'controleurs' => $controleurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="controleur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $controleur = new Controleur();
        $form = $this->createForm(ControleurType::class, $controleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($controleur);
            $entityManager->flush();

            return $this->redirectToRoute('controleur_index');
        }

        return $this->render('controleur/new.html.twig', [
            'controleur' => $controleur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="controleur_show", methods={"GET"})
     */
    public function show(Controleur $controleur): Response
    {
        return $this->render('controleur/show.html.twig', [
            'controleur' => $controleur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="controleur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Controleur $controleur): Response
    {
        $form = $this->createForm(ControleurType::class, $controleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('controleur_index');
        }

        return $this->render('controleur/edit.html.twig', [
            'controleur' => $controleur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="controleur_delete", methods={"POST"})
     */
    public function delete(Request $request, Controleur $controleur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controleur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($controleur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('controleur_index');
    }
}
