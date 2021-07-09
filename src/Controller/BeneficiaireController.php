<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Departements;
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
        $beneficiaireAPAginer = $beneficiaireRepository->findClientList($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {

            $ben = $request->get('search_ben')['name'];
            $beneficiaireAPAginer = $beneficiaireRepository->findBySearch($this->getUser(), $ben);
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
                for ($i = 2; $i <= 5 - 1; $i++) {
                    $beneficiaire = new Beneficiaire();
                    $beneficiaire->setClient($client);
                    $beneficiaire->setName($rows[$i][4]);
                    $beneficiaire->setPrenom($rows[$i][5]);
                    $beneficiaire->setAdresse($rows[$i][6]);
                    $codePostal = $rows[$i][7];

                    $dep = $deprep->DepartmentClient(mb_strimwidth($codePostal,0,2));
                    dump($dep->getName());
                    $beneficiaire->setCodePostal(mb_strimwidth($codePostal,0,2));
                    $beneficiaire->setVille($rows[$i][8]);
                    $em->persist($beneficiaire);
                }
                $em->flush();
                // on supprime le fichier
                $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                unlink($fichierSupp);
                $this->addFlash('success', 'la liste a été correctement inserée');
                return $this->render('beneficiaire/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('beneficiaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
