<?php

namespace App\service;

use App\Repository\ReferenceRepository;
use DateInterval;
use DateTime;
use phpDocumentor\Reflection\Types\This;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    const TOKEN_API = 'programme_at_solupactcom_659f359a7245847e099fffb4c1abf561407f2a72';
    private $httpClient;
    private $referenceRepository;

    public function __construct(HttpClientInterface $httpClient, ReferenceRepository $referenceRepository)
    {
        $this->httpClient = $httpClient;
        $this->referenceRepository = $referenceRepository;
    }
//            'https://www.kizeoforms.com/rest/v3/forms/776032/data/search',
//            'https://www.kizeoforms.com/rest/v3/users',
//            'https://www.kizeoforms.com/rest/v3/forms/776032/data/unread/action/10?format=simple&Authorization=programme_at_solupactcom_659f359a7245847e099fffb4c1abf561407f2a72',

// push un beneficiaire lors de la prise du rendez-vous permet a kizeo de s'incrementer d'un ligne a controler
    public function push($b, $c, $opesimilaire)
    {

//        donne unique pour une beneficiaire

        if (!$b->getPersonneMorale()){
            $dataUnitairePourChacun =
                [
                    "fields" => [
                        "controleur" => [
                            "value" => $c->getNom() . ' ' . $c->getPrenom()
                        ],
                        "version_du_coup_de_pouce" => [
                            "value" => $b->getVersionCoupDePouce()
                        ],
                        "grand_precaire_precaire_c" => [
                            "value" => $b->getGrandPrecairePrecaireClassique()
                        ],
                        "reference_emmy_de_la_demande" => [
                            "value" => $b->getReferenceEmmyDemande()
                        ],
                        "nom_du_beneficiaire_de_l_ope" => [
                            "value" => $b->getName()
                        ],
                        "prenom_du_beneficiaire_de_l_o" => [
                            "value" => $b->getPrenom()
                        ],
                        "adresse_de_l_operation" => [
                            "value" => $b->getAdresse()
                        ],
                        "code_postal1" => [
                            "value" => $b->getCodePostal()
                        ],
                        "ville1" => [
                            "value" => $b->getVille()
                        ],
                        "telephone" => [
                            "value" => $b->getTelephone()
                        ],
                        "adresse_email" => [
                            "value" => $b->getEmail()
                        ],
                        "nature_de_la_bonification" => [
                            "value" => $b->getNatureBonification()
                        ],
                        "siren_du_professionnel" => [
                            "value" => $b->getSirenDuProfesionnel()
                        ],
                        "raison_sociale_du_professionn" => [
                            "value" => $b->getRaisonSocialDuProfessionnel()
                        ],
                        "siren_du_sous_traitant" => [
                            "value" => $b->getSirenSousTraitant()
                        ],
                        "raison_sociale_du_sous_traita" => [
                            "value" => $b->getRaisonSocialeSousTraitant()
                        ],
                        "nature_du_role" => [
                            "value" => $b->getNatureDuRoleActifIncitatif()
                        ],
                        "siren_de_l_organisme_de_contr" => [
                            "value" => $b->getSirenOrganismeControle()
                        ],
                        "raison_sociale_de_l_organisme" => [
                            "value" => $b->getRaisonSocialeOrganismeControle()
                        ],
                        "siret_de_l_entreprise_ayant_r" => [
                            "value" => $b->getSiretEntrepriseAyantRealiseOperation()
                        ],
                        "rendez_vous_pris" => [
                            "value" => "oui"
                        ]
                    ]
                ];
        }else{
            $dataUnitairePourChacun =
                [
                    "fields" => [
                        "controleur" => [
                            "value" => $c->getNom() . ' ' . $c->getPrenom()
                        ],
                        "version_du_coup_de_pouce" => [
                            "value" => $b->getVersionCoupDePouce()
                        ],
//                        "reference_de_la_fiche" => [
//                            "value" => $b->getReferenceFicheOperation()
//                        ],
                        "grand_precaire_precaire_c" => [
                            "value" => $b->getGrandPrecairePrecaireClassique()
                        ],
                        "reference_emmy_de_la_demande" => [
                            "value" => $b->getReferenceEmmyDemande()
                        ],
                        "nom_du_beneficiaire_de_l_ope"=>[
                            "value"=>$b->getNomDuSiteBeneficiaireOperation()
                        ],
                        "raison_sociale_du_demandeur" => [
                            "value" => $b->getRaisonSocialeDemandeur()
                        ],
                        "siren_du_demandeur" => [
                            "value" => $b->getSirenDemandeur()
                        ],
                        "adresse_de_l_operation" => [
                            "value" => $b->getAdresse()
                        ],
                        "code_postal1" => [
                            "value" => $b->getCodePostal()
                        ],
                        "ville1" => [
                            "value" => $b->getVille()
                        ],
                        "telephone" => [
                            "value" => $b->getTelephone()
                        ],
                        "adresse_email" => [
                            "value" => $b->getEmail()
                        ],
                        "nature_de_la_bonification" => [
                            "value" => $b->getNatureBonification()
                        ],
                        "siren_du_professionnel" => [
                            "value" => $b->getSirenDuProfesionnel()
                        ],
                        "raison_sociale_du_professionn" => [
                            "value" => $b->getRaisonSocialDuProfessionnel()
                        ],
                        "siren_du_sous_traitant" => [
                            "value" => $b->getSirenSousTraitant()
                        ],
                        "raison_sociale_du_sous_traita" => [
                            "value" => $b->getRaisonSocialeSousTraitant()
                        ],
                        "nature_du_role" => [
                            "value" => $b->getNatureDuRoleActifIncitatif()
                        ],
                        "siren_de_l_organisme_de_contr" => [
                            "value" => $b->getSirenOrganismeControle()
                        ],
                        "raison_sociale_de_l_organisme" => [
                            "value" => $b->getRaisonSocialeOrganismeControle()
                        ],
                        "siret_de_l_entreprise_ayant_r" => [
                            "value" => $b->getSiretEntrepriseAyantRealiseOperation()
                        ],
                        "rendez_vous_pris" => [
                            "value" => "oui"
                        ],
                    ]
                ];
        }

//        donne qui doivent aller sur la bonne case du formulaire suivant la reference de fiche d'operation
        $dataSpecifiquePourChaqueOpe = [];
        $ref = [];
        foreach ($opesimilaire as $ope) {
            $reference = $this->referenceType($ope);
            $data = [

                "reference_interne_ope_$reference" => [
                    "value" => $ope->getReferenceInterne()
                ],
                "volume_hors_precarite_kwh_$reference" => [
                    "value" => $ope->getVolumeHorsPrecarite()
                ],
                "volume_precarite_kwh_$reference" => [
                    "value" => $ope->getVolumePrecarite()
                ],
                "surface_declaree_dans_ah_$reference" => [
                    "value" => $ope->getSurfaceDeclareeDansAHFacture()
                ],
                "type_d_isolant_declare_$reference" => [
                    "value" => $ope->getTypeIsolantDeclare()
                ],
                "marque_et_reference_isol_$reference" => [
                    "value" => $ope->getMarqueEtReferenceIsolantDeclare()
                ],
                "actions_correctives_menee_$reference" => [
                    "value" => $ope->getActionCorrectiveMeneeSuiteAudit()
                ],
                "conformite_apres_correcti_$reference" => [
                    "value" => $ope->getConformiteApresCorrection()
                ],
                "preciser_selon_le_cas_si_$reference" => [
                    "value" => $ope->getOperationRetireOuIssueDossierPrecedent()
                ],
                "commentaires_generaux_$reference" => [
                    "value" => $ope->getCommentaireGeneraux()
                ],
                "valeur_r_lambda_declare_$reference" => [
                    "value" => $ope->getValeurRouLambdadeclare()
                ],
                "epaisseur_min_theorique_$reference" => [
                    "value" => $ope->getEpaisseurMinTheorique()
                ],
                "date_engagement_ope_$reference" => [
                    "value" => $this->apiDate($ope->getDateEngagementOperation())
                ],
                "date_achevement_ope_$reference" => [
                    "value" => $this->apiDate($ope->getDateAchevementOperation())
                ],
                "date_achevement_ope_$reference" => [
                    "value" => $this->apiDate($ope->getDateFacture())
                ],

            ];
//recuperation des ficheoperation de chaque entité
            $fiche = [$ope->getReferenceFicheOperation()];
            array_push($ref, $fiche);
            $dataSpecifiquePourChaqueOpe = $dataSpecifiquePourChaqueOpe + $data;

        }
//        construction de la phrase a envoyer sur le formulaire
        $phraseRefFiche = "";
        for ($i = 0; $i < count($ref); $i++) {
            $phraseRefFiche .= $ref[$i][0] . ", ";
        }
        $ficheope = ["references_de_la_fiche1" => [
            "value" => $phraseRefFiche
            ],
        ];
//        merge du tableau commun et du tableau par ref
        $dataEnvoi = array_merge($dataUnitairePourChacun["fields"], $dataSpecifiquePourChaqueOpe);
//        merge du tableau d'avant avec la nouvelle phrase des ref operation
        $dataEnvoi= array_merge($dataEnvoi,$ficheope);
//        construction du datafinal a envoyer
        $datafinale = ["recipient_user_id" => $c->getKizeoId(), "fields" => $dataEnvoi];
//        recuperation de l'adresse du formulaire'
        $idFormKizeo = $this->referenceRepository->findOneBy(['reference' => $b->getReferenceEmmyDemande()]);
        $kizeo = $idFormKizeo->getIdKizeoForm();

//        envoie du push vers kizeo avec recuperation de l'id kizeo par beneficiaire
        $response = $this->httpClient->request(
            'POST',
            "https://www.kizeoforms.com/rest/v3/forms/$kizeo/push",
            [
                'headers' =>
                    ['Authorization' => self::TOKEN_API],
                'json' => $datafinale
            ],
        );
        $data = json_decode($response->getContent(), TRUE);
        return $data['data']['data_id'];
    }

    public function referenceType($b)
    {
        return $reference = substr($b->getReferenceFicheOperation(), -3);
    }

    public function apiDate($date){
        if ($date){
            $newDate = explode("/", $date);
            return $newDate[2]."/".$newDate[1]."/".$newDate[0];
        }
        else{
            return null;
        }

    }


}