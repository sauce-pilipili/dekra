<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Reference;
use App\Form\DataBeneficiaireType;
use App\Form\SearchBenType;
use App\Repository\BeneficiaireRepository;
use App\Repository\DepartementsRepository;
use App\Repository\ReferenceRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Security("is_granted('ROLE_USER')")
 */
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
    public function new(Request $request, UserRepository $userRepository, DepartementsRepository $deprep, SpecialiteRepository $specialiteRepository, ReferenceRepository $referenceRepository): Response
        {$em = $this->getDoctrine()->getManager();
        $client = $this->getUser();


        $form = $this->createForm(DataBeneficiaireType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //recuperation du fichier
            $fichier = $request->files->get('data_beneficiaire');
            // on boucle sur le bag

            foreach ($fichier as $fic) {
                //je nomme le fichier feuille avec son extension
                $document = 'feuille.' . $fic->guessExtension();
                // je l'envoie dans public upload
                $fic->move($this->getParameter('document_directory'), $document);
                //creation du reader pour lire le fichier
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                // autorisaiton de lecture des info
                $reader->setReadDataOnly(true);
                $directory = $this->getParameter('document_directory');
                // debut de la lecture du fichier par appel
                $spreadsheet = $reader->load($directory . '/feuille.xlsx');
                // lecture du fichier par la case voulu
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                $feuilleLength = $spreadsheet->getActiveSheet()->getHighestDataRow();
                //selection de la feuille personnes physiques ou morales?


//calucul longueur du fichier
                for ($i = 1; $i <= $feuilleLength - 1; $i++){
                    if (empty($rows[$i][0])
                        &&empty($rows[$i][1])
                        &&empty($rows[$i][3])
                        &&empty($rows[$i][4])
                        &&empty($rows[$i][5])
                        &&empty($rows[$i][6])
                        &&empty($rows[$i][7])
                        &&empty($rows[$i][8])
                        &&empty($rows[$i][9])
                        &&empty($rows[$i][10])
                        &&empty($rows[$i][11])
                        &&empty($rows[$i][12])
                        &&empty($rows[$i][13])
                        &&empty($rows[$i][14])
                        &&empty($rows[$i][15])
                        &&empty($rows[$i][16])){
                        $feuilleLength = $i;

                    }
                }
// gestion erreur colonne ref Emmy
                for ($o = 1; $o <= $feuilleLength - 1; $o++){
                    if (empty($rows[$o][2])){
                        $this->addFlash('danger', 'La feuille que vous essayez d\'insérer contient une erreur de référence Emmy à la ligne: '.$o);
                        $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                        unlink($fichierSupp);
                        return $this->render('beneficiaire/new.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                }
// gestion erreur reference emmy differente sur la meme feuille
                for ($p = 1; $p <= $feuilleLength - 1; $p++){
                    if ($p>2 && $rows[$p][2]!= $rows[$p-1][2] ){
                        $this->addFlash('danger', 'La feuille que vous essayez d\'insérer contient deux références Emmy différentes: '.$o);
                        $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                        unlink($fichierSupp);
                        return $this->render('beneficiaire/new.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                }
//               gestion erreur reference Emmy deja insérée
                if($referenceRepository->findOneBy(['reference'=> $rows[1][2]]) != null ){
                    $this->addFlash('danger', 'La feuille que vous essayez d\'insérer a déjà été insérée');
                    $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                    unlink($fichierSupp);
                    return $this->render('beneficiaire/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                };
                $referenceEmmy = new Reference();
                $referenceEmmy->setReference($rows[1][2]);
                $referenceEmmy->setComplet(0);
                $em->persist($referenceEmmy);
                if ($form->get('select')->getData() == 'physique') {

                    if ($spreadsheet->getActiveSheet()->getHighestColumn() != 'AI') {
                        $this->addFlash('danger', 'La feuille que vous essayez d\'insérer contient une liste de personnes morales');
                        $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                        unlink($fichierSupp);
                        return $this->render('beneficiaire/new.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    for ($i = 1; $i <= $feuilleLength - 1; $i++) {
                        $beneficiaire = new Beneficiaire();
                        $beneficiaire->setPersonneMorale(0);
                        $beneficiaire->setStatut(0);
                        $beneficiaire->setClient($client);
                        $beneficiaire->setRaisonSocialeDemandeur($rows[$i][0]);
                        $beneficiaire->setSirenDemandeur($rows[$i][1]);
                        $beneficiaire->setReferenceEmmyDemande($rows[$i][2]);
                        $beneficiaire->setReferenceInterne($rows[$i][3]);
                        $beneficiaire->setName($rows[$i][4]);
                        $beneficiaire->setPrenom($rows[$i][5]);
                        $beneficiaire->setAdresse($rows[$i][6]);
                        $beneficiaire->setCodePostal($rows[$i][7]);
                        $dep = $deprep->findOneBy(['numero' => $rows[$i][8]]);
                        //gestion erreur sur departement
                        if (!$dep) {
                            $this->addFlash('danger', 'le numéro de département du client ' . $rows[$i][4] . ' ligne ' . $i . ' colonne I n\'est pas valable');
                            $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                            unlink($fichierSupp);
                            return $this->render('beneficiaire/new.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                        $beneficiaire->addDepartement($dep);
                        $beneficiaire->setVille($rows[$i][9]);
                        $beneficiaire->setTelephone($rows[$i][10]);
                        $beneficiaire->setEmail($rows[$i][11]);
                        $beneficiaire->setVolumeHorsPrecarite($rows[$i][12]);
                        $beneficiaire->setVolumePrecarite($rows[$i][13]);
                        $beneficiaire->setReferenceFicheOperation($rows[$i][14]);
                        $spe = $specialiteRepository->findOneBy(['referenceOperation' => $rows[$i][14]]);
                        // gestion erreur sur la specialité
                        if (!$spe) {
                            $this->addFlash('danger', 'la référence de la fiche d\'opération standardisée du client ' . $rows[$i][4] . ' ligne ' . $i . ' colonne O n\'est pas valable');
                            $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                            unlink($fichierSupp);
                            return $this->render('beneficiaire/new.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                        $beneficiaire->setDateEngagementOperation($rows[$i][15]);
                        $beneficiaire->setDateFacture($rows[$i][16]);
                        $beneficiaire->setNatureBonification($rows[$i][17]);
                        $beneficiaire->setSirenDuProfesionnel($rows[$i][18]);
                        $beneficiaire->setRaisonSocialDuProfessionnel($rows[$i][19]);
                        $beneficiaire->setSirenSousTraitant($rows[$i][20]);
                        $beneficiaire->setRaisonSocialeSousTraitant($rows[$i][21]);
                        $beneficiaire->setNatureDuRoleActifIncitatif($rows[$i][22]);
                        $beneficiaire->setSirenOrganismeControle($rows[$i][23]);
                        $beneficiaire->setRaisonSocialeOrganismeControle($rows[$i][24]);
                        $beneficiaire->setSiretEntrepriseAyantRealiseOperation($rows[$i][25]);
                        $beneficiaire->setSurfaceDeclareeDansAHFacture($rows[$i][26]);
                        $beneficiaire->setTypeIsolantDeclare($rows[$i][27]);
                        $beneficiaire->setMarqueEtReferenceIsolantDeclare($rows[$i][28]);
                        $beneficiaire->setActionCorrectiveMeneeSuiteAudit($rows[$i][29]);
                        $beneficiaire->setConformiteApresCorrection($rows[$i][30]);
                        $beneficiaire->setOperationRetireOuIssueDossierPrecedent($rows[$i][31]);
                        $beneficiaire->setCommentaireGeneraux($rows[$i][32]);
                        $beneficiaire->setGrandPrecairePrecaireClassique($rows[$i][33]);
                        $beneficiaire->setVersionCoupDePouce($rows[$i][34]);
                        $em->persist($beneficiaire);
                    }
                }
                if ($form->get('select')->getData() == 'morale') {
                    if ($spreadsheet->getActiveSheet()->getHighestColumn() != 'AM') {
                        $this->addFlash('danger', 'La feuille que vous essayez d\'insérer contient une liste de personnes physiques');
                        $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                        unlink($fichierSupp);
                        return $this->render('beneficiaire/new.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    for ($i = 1; $i <= $feuilleLength - 1; $i++) {

                        $beneficiaire = new Beneficiaire();
                        $beneficiaire->setPersonneMorale(1);
                        $beneficiaire->setStatut(0);
                        $beneficiaire->setClient($client);
                        $beneficiaire->setRaisonSocialeDemandeur($rows[$i][0]);
                        $beneficiaire->setSirenDemandeur($rows[$i][1]);
                        $beneficiaire->setReferenceEmmyDemande($rows[$i][2]);
                        $beneficiaire->setReferenceInterne($rows[$i][3]);
                        $beneficiaire->setNomDuSiteBeneficiaireOperation($rows[$i][4]);
                        $beneficiaire->setAdresse($rows[$i][5]);
                        $beneficiaire->setCodePostal($rows[$i][6]);
                        $dep = $deprep->findOneBy(['numero' => $rows[$i][7]]);
                        //gestion erreur sur department
                        if (!$dep) {
                            $this->addFlash('danger', 'le numéro de département '. $rows[$i][7] . ' du client ' . $rows[$i][4] . ' ligne ' . $i . ' colonne I n\'est pas valable');
                            $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                            unlink($fichierSupp);
                            return $this->render('beneficiaire/new.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                        $beneficiaire->addDepartement($dep);
                        $beneficiaire->setVille($rows[$i][8]);
                        $beneficiaire->setTelephone($rows[$i][9]);
                        $beneficiaire->setEmail($rows[$i][10]);
                        $beneficiaire->setRaisonSocialDuBeneficiaireOperation($rows[$i][11]);
                        $beneficiaire->setSirenBeneficiaireOperation($rows[$i][12]);
                        $beneficiaire->setAdresseDuSiegeSocial($rows[$i][13]);
                        $beneficiaire->setCodepostalSiegeSocial($rows[$i][14]);
                        $beneficiaire->setVilleSiegeSocial($rows[$i][15]);
                        $beneficiaire->setVolumeHorsPrecarite($rows[$i][16]);
                        $beneficiaire->setVolumePrecarite($rows[$i][17]);
                        $beneficiaire->setReferenceFicheOperation($rows[$i][18]);
                        $spe = $specialiteRepository->findOneBy(['referenceOperation' => $rows[$i][18]]);
                        // gestion erreur sur la specialité
                        if (!$spe) {
                            $this->addFlash('danger', 'la référence de la fiche d\'opération standardisée du client ' . $rows[$i][4] . ' ligne ' . $i . ' colonne O n\'est pas valable');
                            $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
                            unlink($fichierSupp);
                            return $this->render('beneficiaire/new.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                        $beneficiaire->setDateEngagementOperation($rows[$i][19]);
                        $beneficiaire->setDateAchevementOperation($rows[$i][20]);
                        $beneficiaire->setNatureBonification($rows[$i][21]);
                        $beneficiaire->setSirenDuProfesionnel($rows[$i][22]);
                        $beneficiaire->setRaisonSocialDuProfessionnel($rows[$i][23]);
                        $beneficiaire->setSirenSousTraitant($rows[$i][24]);
                        $beneficiaire->setRaisonSocialeSousTraitant($rows[$i][25]);
                        $beneficiaire->setNatureDuRoleActifIncitatif($rows[$i][26]);
                        $beneficiaire->setSirenOrganismeControle($rows[$i][27]);
                        $beneficiaire->setRaisonSocialeOrganismeControle($rows[$i][28]);
                        $beneficiaire->setSiretEntrepriseAyantRealiseOperation($rows[$i][29]);
                        $beneficiaire->setSurfaceDeclareeDansAHFacture($rows[$i][26]);
                        $beneficiaire->setTypeIsolantDeclare($rows[$i][27]);
                        $beneficiaire->setMarqueEtReferenceIsolantDeclare($rows[$i][28]);
                        $beneficiaire->setActionCorrectiveMeneeSuiteAudit($rows[$i][29]);
                        $beneficiaire->setConformiteApresCorrection($rows[$i][30]);
                        $beneficiaire->setOperationRetireOuIssueDossierPrecedent($rows[$i][31]);
                        $beneficiaire->setCommentaireGeneraux($rows[$i][32]);
                        $beneficiaire->setGrandPrecairePrecaireClassique($rows[$i][33]);
                        $beneficiaire->setVersionCoupDePouce($rows[$i][34]);

                        $em->persist($beneficiaire);
                    }
                }
            }

            $em->flush();
            // on supprime le fichier
            $fichierSupp = ($this->getParameter('document_directory') . '/' . $document);
            unlink($fichierSupp);
            $this->addFlash('success', 'La liste de bénéficiaires a été inserée avec succès !');
            return $this->render('beneficiaire/new.html.twig', [
                'form' => $form->createView(),
            ]);
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
