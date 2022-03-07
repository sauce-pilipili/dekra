<?php

namespace App\service;

use App\Repository\ReferenceRepository;
use DateInterval;
use DateTime;
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

    public function push($b, $c)
    {
        if ($b->getPersonneMorale() == 0) {
            $this->pushPhysique($b, $c);
        } else {
            $this->pushMorale($b, $c);
        }
    }
//            'https://www.kizeoforms.com/rest/v3/forms/776032/data/search',
//            'https://www.kizeoforms.com/rest/v3/users',
//            'https://www.kizeoforms.com/rest/v3/forms/776032/data/unread/action/10?format=simple&Authorization=programme_at_solupactcom_659f359a7245847e099fffb4c1abf561407f2a72',

// push un beneficiaire lors de la prise du rendez-vous permet a kizeo de s'incrementer d'un ligne a controler
    public function pushPhysique($b, $c)
    {
        $time = new DateTime(date_format($b->getRdv(),'Y-m-d H:i:s' ));
        $time->add(new DateInterval('PT1H'));

        dd($b->getRdv(),$time);
        //        recupere le 101 102 103 etc... injection dynamique a la fin du curl
        $reference = $this->referenceType($b);
        $idFormKizeo = $this->referenceRepository->findOneBy(['reference'=> $b->getReferenceEmmyDemande()]);
        $kizeo = $idFormKizeo->getIdKizeoForm();
        $response = $this->httpClient->request(
            'POST',
            "https://www.kizeoforms.com/rest/v3/forms/$kizeo/push",
            [
                'headers' =>
                    ['Authorization' => self::TOKEN_API],
                'json' => [
                    "recipient_user_id" => $c->getKizeoId(),
                    "fields" => [
                        "controleur" => [
                            "value" => $c->getNom() . ' ' . $c->getPrenom()
                        ],
                        "version_du_coup_de_pouce" => [
                            "value" => $b->getVersionCoupDePouce()
                        ],
                        "reference_de_la_fiche" => [
                            "value" => $b->getReferenceFicheOperation()
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
                        ],

//                        todo comment gerer ca?   "operation_selectionnee_aleato"

//                    ********************* dynamique suivant reference ************
                        "reference_interne_ope_$reference" => [
                            "value" => $b->getReferenceInterne()
                        ],
                        "volume_hors_precarite_kwh_$reference" => [
                            "value" => $b->getVolumeHorsPrecarite()
                        ],
                        "volume_precarite_kwh_$reference" => [
                            "value" => $b->getVolumePrecarite()
                        ],
                        "surface_declaree_dans_ah_$reference" => [
                            "value" => $b->getSurfaceDeclareeDansAHFacture()
                        ],
                        "type_d_isolant_declare_$reference" => [
                            "value" => $b->getTypeIsolantDeclare()
                        ],
                        "marque_et_reference_isol_$reference" => [
                            "value" => $b->getMarqueEtReferenceIsolantDeclare()
                        ],
                        "actions_correctives_menee_$reference" => [
                            "value" => $b->getActionCorrectiveMeneeSuiteAudit()
                        ],
                        "conformite_apres_correcti_$reference" => [
                            "value" => $b->getConformiteApresCorrection()
                        ],
                        "preciser_selon_le_cas_si_$reference" => [
                            "value" => $b->getOperationRetireOuIssueDossierPrecedent()
                        ],
                        "commentaires_generaux_$reference" => [
                            "value" => $b->getCommentaireGeneraux()
                        ],
//                        ********************fin dynamique suivant reference**********
                    ]
                ]
            ],
        );
        $data = json_decode($response->getContent(), TRUE);
        return $data['data']['data_id'];
    }


    public function pushMorale($b, $c)
    {
//        recupere le 101 102 103 etc... injection dynamique a la fin du curl
        $reference = $this->referenceType($b);
        $idFormKizeo = $this->referenceRepository->findOneBy(['reference']);
        $kizeo = $idFormKizeo->getIdKizeoForm();


        $response = $this->httpClient->request(
            'POST',
            "https://www.kizeoforms.com/rest/v3/forms/$kizeo/push",
            [
                'headers' =>
                    ['Authorization' => self::TOKEN_API],
                'json' => [
                    "recipient_user_id" => $c->getKizeoId(),
                    "fields" => [
                        "controleur" => [
                            "value" => $c->getNom() . ' ' . $c->getPrenom()
                        ],
                        "version_du_coup_de_pouce" => [
                            "value" => $b->getVersionCoupDePouce()
                        ],
                        "reference_de_la_fiche" => [
                            "value" => $b->getReferenceFicheOperation()
                        ],
                        "grand_precaire_precaire_c" => [
                            "value" => $b->getGrandPrecairePrecaireClassique()
                        ],
                        "reference_emmy_de_la_demande" => [
                            "value" => $b->getReferenceEmmyDemande()
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
                        //                    ********************* dynamique suivant reference ************
                        "reference_interne_ope_$reference" => [
                            "value" => $b->getReferenceInterne()
                        ],
                        "volume_hors_precarite_kwh_$reference" => [
                            "value" => $b->getVolumeHorsPrecarite()
                        ],
                        "volume_precarite_kwh_$reference" => [
                            "value" => $b->getVolumePrecarite()
                        ],
                        "surface_declaree_dans_ah_$reference" => [
                            "value" => $b->getSurfaceDeclareeDansAHFacture()
                        ],
                        "type_d_isolant_declare_$reference" => [
                            "value" => $b->getTypeIsolantDeclare()
                        ],
                        "marque_et_reference_isol_$reference" => [
                            "value" => $b->getMarqueEtReferenceIsolantDeclare()
                        ],
                        "actions_correctives_menee_$reference" => [
                            "value" => $b->getActionCorrectiveMeneeSuiteAudit()
                        ],
                        "conformite_apres_correcti_$reference" => [
                            "value" => $b->getConformiteApresCorrection()
                        ],
                        "preciser_selon_le_cas_si_$reference" => [
                            "value" => $b->getOperationRetireOuIssueDossierPrecedent()
                        ],
                        "commentaires_generaux_$reference" => [
                            "value" => $b->getCommentaireGeneraux()
                        ],
//                        ********************fin dynamique suivant reference**********
                    ]
                ]
            ],
        );
        $data = json_decode($response->getContent(), TRUE);
        return $data['data']['data_id'];
    }

    public function referenceType($b)
    {
        return $reference = substr($b->getReferenceFicheOperation(), -3);
    }


}