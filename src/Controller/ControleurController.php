<?php

namespace App\Controller;

use App\Entity\Controleur;
use App\Entity\Data;
use App\Form\ControleurType;
use App\Form\DataType;
use App\Repository\ControleurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class ControleurController extends AbstractController
{
//    valide pour v2
    /**
     * @Route("/controleur/index", name="controleur_index", methods={"GET","POST"})
     */
    public function index(Request $request,ControleurRepository $controleurRepository): Response
    {   //formulaire recherche
        $data = new Data();
        $form = $this->createForm(DataType::class,$data);
        $form->handleRequest($request);
        //pagination page
        $controleur = $controleurRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $dep = $request->get('data')['name'];
            $spe = $request->get('data')['specialite'];
            //pagination page avec recherche
            $controleursAPaginer = $controleurRepository->findControleurByData($dep,$spe);
        }
        return $this->render('controleur/index.html.twig', [
            'controleurs' => $controleur,
            'form'=> $form->createView(),
        ]);
    }

//    valide pour v2
    /**
     * @Route("/controleur/new", name="controleur_new", methods={"GET","POST"})
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
            $this->addFlash('success','La fiche contrôleur a été créée avec succès !');
            return $this->redirectToRoute('controleur_index');
        }

        return $this->render('controleur/new.html.twig', [
            'controleur' => $controleur,
            'form' => $form->createView(),
        ]);
    }
    //    valide pour v2
    /**
     * @Route("/controleur/{id}/edit", name="controleur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Controleur $controleur): Response
    {
        $form = $this->createForm(ControleurType::class, $controleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','La fiche contrôleur a été modifiée avec succès !');
            return $this->redirectToRoute('controleur_index');
        }

        return $this->render('controleur/edit.html.twig', [
            'controleur' => $controleur,
            'form' => $form->createView(),
        ]);
    }
//    valide pour v2
    /**
     * @Route("/controleur/{id}", name="controleur_delete", methods={"POST"})
     */
    public function delete(Request $request, Controleur $controleur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controleur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($controleur);
            $entityManager->flush();
        }
        $this->addFlash('success','La fiche contrôleur a été supprimée avec succès !');
        return $this->redirectToRoute('controleur_index');
    }
}
