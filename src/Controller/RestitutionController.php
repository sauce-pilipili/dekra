<?php

namespace App\Controller;

use App\Form\SearchBenType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ReferenceRepository;
use App\service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RestitutionController extends AbstractController
{


    /**
     * @Route("/visuel/{id}", name="visuel")
     */
    public function client($id, Request $request, BeneficiaireRepository $beneficiaireRepository, ReferenceRepository $referenceRepository, HttpClientInterface $httpClient, ApiService $api): Response
    {
        $form = $this->createForm(SearchBenType::class);
        $form->handleRequest($request);

        $beneficiaire = $beneficiaireRepository->find(19679);
        $idFormKizeo = $referenceRepository->findOneBy(['reference' => $beneficiaire->getReferenceEmmyDemande()]);
        $kizeo = $idFormKizeo->getIdKizeoForm();
        $ben = $beneficiaire->getKizeoID();

        $response = $httpClient->request(
            'GET',
            "https://www.kizeoforms.com/rest/v3/forms/$kizeo/data/$ben?format=simple",
//            "https://www.kizeoforms.com/rest/v3/forms/$kizeo/data/all",
            [
                'headers' =>
                    ['Authorization' => 'programme_at_solupactcom_659f359a7245847e099fffb4c1abf561407f2a72']
            ]);

        $retourApi = $response->toArray()["data"]["fields"];

        dd($retourApi);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:C1')->getFont()->getColor()->setRGB(255, 0, 0);


        $sheet->setCellValue('A1', $response->toArray()["data"]["fields"]["controleur"]["value"]);
        $sheet->setCellValue('B1', $response->toArray()["data"]["fields"]["reference_de_la_fiche"]["value"]);
        $sheet->setCellValue('C1', $response->toArray()["data"]["fields"]["reference_emmy_de_la_demande"]["value"]);


        $writer = new Xlsx($spreadsheet);
        $writer->save('restitution.xlsx');
        $response = new BinaryFileResponse('restitution.xlsx');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'restitution.xlsx');
        return $response;


//        dd($response->getContent(), $response->getStatusCode());
//
//        $beneficiaire = $beneficiaireRepository->findClientList($id);
//
//        return $this->render('beneficiaire/index.html.twig', [
//            'beneficiaires' => $beneficiaire,
//            'form' => $form->createView()
//        ]);
    }
}
