<?php

namespace App\Controller;

use App\Form\SearchBenType;
use App\Repository\BeneficiaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RestitutionController extends AbstractController
{
    /**
     * @Route("/visuel/{id}", name="visuel")
     */
    public function client($id,Request $request, BeneficiaireRepository $beneficiaireRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchBenType::class);
        $form->handleRequest($request);
        $beneficiaireAPAginer = $beneficiaireRepository->findClientList($id);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $ben = $request->get('search_ben')['name'];
//                $beneficiaireAPAginer = $beneficiaireRepository->findBySearch($this->getUser(), $ben);
//            $beneficiaire = $paginator->paginate($beneficiaireAPAginer, $request->query->getInt('page', 1), 6);
//            return $this->render('beneficiaire/index.html.twig', [
//                'beneficiaires' => $beneficiaire,
//                'form' => $form->createView()
//            ]);
//        }
        $beneficiaire = $paginator->paginate($beneficiaireAPAginer, $request->query->getInt('page', 1), 6);
        return $this->render('beneficiaire/index.html.twig', [
            'beneficiaires' => $beneficiaire,
            'form' => $form->createView()
        ]);
    }
}
