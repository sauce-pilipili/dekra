<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\DataBeneficiaireType;
use App\Form\SearchBenType;
use App\Repository\BeneficiaireRepository;
use App\Repository\DepartementsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BeneficiaireController extends AbstractController
{
    /**
     * @Route("/beneficiaire", name="beneficiaire", methods={"GET","POST"})
     */
    public function index(Request $request, BeneficiaireRepository $beneficiaireRepository, PaginatorInterface $paginator): Response
    {

        $form = $this->createForm(SearchBenType::class);
        $form->handleRequest($request);
        $beneficiaireAPAginer = $beneficiaireRepository->ClientListAdmin();
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $beneficiaireAPAginer = $beneficiaireRepository->findClientList($this->getUser());

        }

        if ($form->isSubmitted() && $form->isValid()) {
            $ben = $request->get('search_ben')['name'];
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CALL_CENTER')) {
                $beneficiaireAPAginer = $beneficiaireRepository->findByName($ben);
            } else {
                $beneficiaireAPAginer = $beneficiaireRepository->findBySearch($this->getUser(), $ben);
            }
            $beneficiaire = $paginator->paginate($beneficiaireAPAginer, $request->query->getInt('page', 1), 6);
            return $this->render('beneficiaire/index.html.twig', [
                'beneficiaires' => $beneficiaire,
                'form' => $form->createView()
            ]);
        }

        $beneficiaire = $paginator->paginate($beneficiaireAPAginer, $request->query->getInt('page', 1), 6);
        return $this->render('beneficiaire/index.html.twig', [
            'beneficiaires' => $beneficiaire,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/beneficiaire/new", name="beneficiaire_new", methods={"GET","POST"})
     */
    public function new(Request $request, DepartementsRepository $deprep): Response
    {
        $client = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DataBeneficiaireType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //recuperation du fichier
            $fichier = $request->files->get('data_beneficiaire');
            // on oboucle sur le bag
            foreach ($fichier as $fic) {
                //je nomme le fichier feuille avec son extension
                $document = 'feuille.' . $fic->guessExtension();
                // je l'envoie dans public upload
                $fic->move($this->getParameter('document_directory'), $document);

                //creation du reader pour lire le fichier
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
                // autorisaiton de lecture des info
                $reader->setReadDataOnly(true);

                $directory = $this->getParameter('document_directory');
                // debut de la lecture du fichier par appel
                $spreadsheet = $reader->load($directory . '/feuille.xls');
                // lecture du fichier par la case voulu
                //dd($spreadsheet->getActiveSheet()->getHighestColumn(), $spreadsheet->getActiveSheet()->getHighestrow());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                $feuilleLength = $spreadsheet->getActiveSheet()->getHighestRow();
//                for ($i = 2; $i <= $feuilleLength - 1; $i++) {
                for ($i = 2; $i <= $feuilleLength - 1; $i++) {
                    $beneficiaire = new Beneficiaire();
                    $beneficiaire->setStatut(0);
                    $beneficiaire->setClient($client);
                    $beneficiaire->setName($rows[$i][4]);
                    $beneficiaire->setPrenom($rows[$i][5]);
                    $beneficiaire->setAdresse($rows[$i][6]);
                    $codePostal = $rows[$i][7];
                    $departement = $deprep->findOneBy(['numero' => mb_strimwidth($codePostal, 0, 2)]);
//                    dd($beneficiaire->getName(),$beneficiaire->getPrenom(),$departement,$deprep->DepartmentClient(mb_strimwidth($codePostal,0,2)));
                    $beneficiaire->addDepartement($departement);
                    $beneficiaire->setCodePostal($codePostal);
                    $beneficiaire->setVille($rows[$i][8]);
                    $em->persist($beneficiaire);
                }
                $em->flush();
                // on supprime le fichier
                $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                unlink($fichierSupp);
                $this->addFlash('success', 'La liste bénéficiaires a été inserée avec succès !');
                return $this->render('beneficiaire/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('beneficiaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/beneficiaire/show{id}", name="beneficiaire_show", methods={"GET"})
     */
    public function show($id, BeneficiaireRepository $beneficiaireRepository)
    {
        $beneficiaire = $beneficiaireRepository->show($id);

        return $this->render('beneficiaire/show.html.twig', [
            'beneficiaire' => $beneficiaire
        ]);
    }
    /**
     * @Route("/beneficiaire{id}", name="beneficiaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Beneficiaire $beneficiaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $beneficiaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($beneficiaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('beneficiaire');
    }
}
