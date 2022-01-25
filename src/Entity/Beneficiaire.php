<?php

namespace App\Entity;

use App\Repository\BeneficiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeneficiaireRepository::class)
 */
class Beneficiaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="beneficiaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity=Departements::class, inversedBy="beneficiaires",cascade={"persist"})
     */
    private $departement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rdv;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RaisonSocialeDemandeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SirenDemandeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReferenceEmmyDemande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceInterne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $volumeHorsPrecarite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $volumePrecarite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referenceFicheOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateEngagementOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateFacture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $natureBonification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SirenDuProfesionnel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSocialDuProfessionnel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sirenSousTraitant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSocialeSousTraitant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $natureDuRoleActifIncitatif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sirenOrganismeControle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSocialeOrganismeControle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SiretEntrepriseAyantRealiseOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $actionCorrectiveMeneeSuiteAudit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conformiteApresCorrection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $operationRetireOuIssueDossierPrecedent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CommentaireGeneraux;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grandPrecairePrecaireClassique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $versionCoupDePouce;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomDuSiteBeneficiaireOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSocialDuBeneficiaireOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sirenBeneficiaireOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseDuSiegeSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codepostalSiegeSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villeSiegeSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateAchevementOperation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $personneMorale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroLot;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SurfaceDeclareeDansAHFacture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeIsolantDeclare;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marqueEtReferenceIsolantDeclare;


    public function __construct()
    {
        $this->departement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|departements[]
     */
    public function getDepartement(): Collection
    {
        return $this->departement;
    }

    public function addDepartement(departements $departement): self
    {
        if (!$this->departement->contains($departement)) {
            $this->departement[] = $departement;
        }

        return $this;
    }

    public function removeDepartement(departements $departement): self
    {
        $this->departement->removeElement($departement);

        return $this;
    }

    public function getRdv(): ?\DateTimeInterface
    {
        return $this->rdv;
    }

    public function setRdv(?\DateTimeInterface $rdv): self
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getRaisonSocialeDemandeur(): ?string
    {
        return $this->RaisonSocialeDemandeur;
    }

    public function setRaisonSocialeDemandeur(?string $RaisonSocialeDemandeur): self
    {
        $this->RaisonSocialeDemandeur = $RaisonSocialeDemandeur;

        return $this;
    }

    public function getSirenDemandeur(): ?string
    {
        return $this->SirenDemandeur;
    }

    public function setSirenDemandeur(?string $SirenDemandeur): self
    {
        $this->SirenDemandeur = $SirenDemandeur;

        return $this;
    }

    public function getReferenceEmmyDemande(): ?string
    {
        return $this->ReferenceEmmyDemande;
    }

    public function setReferenceEmmyDemande(?string $ReferenceEmmyDemande): self
    {
        $this->ReferenceEmmyDemande = $ReferenceEmmyDemande;

        return $this;
    }

    public function getReferenceInterne(): ?string
    {
        return $this->referenceInterne;
    }

    public function setReferenceInterne(?string $referenceInterne): self
    {
        $this->referenceInterne = $referenceInterne;

        return $this;
    }

    public function getVolumeHorsPrecarite(): ?string
    {
        return $this->volumeHorsPrecarite;
    }

    public function setVolumeHorsPrecarite(?string $volumeHorsPrecarite): self
    {
        $this->volumeHorsPrecarite = $volumeHorsPrecarite;

        return $this;
    }

    public function getVolumePrecarite(): ?string
    {
        return $this->volumePrecarite;
    }

    public function setVolumePrecarite(?string $volumePrecarite): self
    {
        $this->volumePrecarite = $volumePrecarite;
        return $this;
    }

    public function getReferenceFicheOperation(): ?string
    {
        return $this->referenceFicheOperation;
    }

    public function setReferenceFicheOperation(string $referenceFicheOperation): self
    {
        $this->referenceFicheOperation = $referenceFicheOperation;

        return $this;
    }

    public function getDateEngagementOperation(): ?string
    {
        return $this->dateEngagementOperation;
    }

    public function setDateEngagementOperation(?string $dateEngagementOperation): self
    {
        $this->dateEngagementOperation = $dateEngagementOperation;

        return $this;
    }

    public function getDateFacture(): ?string
    {
        return $this->dateFacture;
    }

    public function setDateFacture(?string $dateFacture): self
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getNatureBonification(): ?string
    {
        return $this->natureBonification;
    }

    public function setNatureBonification(?string $natureBonification): self
    {
        $this->natureBonification = $natureBonification;

        return $this;
    }

    public function getSirenDuProfesionnel(): ?string
    {
        return $this->SirenDuProfesionnel;
    }

    public function setSirenDuProfesionnel(?string $SirenDuProfesionnel): self
    {
        $this->SirenDuProfesionnel = $SirenDuProfesionnel;

        return $this;
    }

    public function getRaisonSocialDuProfessionnel(): ?string
    {
        return $this->raisonSocialDuProfessionnel;
    }

    public function setRaisonSocialDuProfessionnel(?string $raisonSocialDuProfessionnel): self
    {
        $this->raisonSocialDuProfessionnel = $raisonSocialDuProfessionnel;

        return $this;
    }

    public function getSirenSousTraitant(): ?string
    {
        return $this->sirenSousTraitant;
    }

    public function setSirenSousTraitant(?string $sirenSousTraitant): self
    {
        $this->sirenSousTraitant = $sirenSousTraitant;

        return $this;
    }

    public function getRaisonSocialeSousTraitant(): ?string
    {
        return $this->raisonSocialeSousTraitant;
    }

    public function setRaisonSocialeSousTraitant(?string $raisonSocialeSousTraitant): self
    {
        $this->raisonSocialeSousTraitant = $raisonSocialeSousTraitant;

        return $this;
    }

    public function getNatureDuRoleActifIncitatif(): ?string
    {
        return $this->natureDuRoleActifIncitatif;
    }

    public function setNatureDuRoleActifIncitatif(?string $natureDuRoleActifIncitatif): self
    {
        $this->natureDuRoleActifIncitatif = $natureDuRoleActifIncitatif;

        return $this;
    }

    public function getSirenOrganismeControle(): ?string
    {
        return $this->sirenOrganismeControle;
    }

    public function setSirenOrganismeControle(?string $sirenOrganismeControle): self
    {
        $this->sirenOrganismeControle = $sirenOrganismeControle;

        return $this;
    }

    public function getRaisonSocialeOrganismeControle(): ?string
    {
        return $this->raisonSocialeOrganismeControle;
    }

    public function setRaisonSocialeOrganismeControle(?string $raisonSocialeOrganismeControle): self
    {
        $this->raisonSocialeOrganismeControle = $raisonSocialeOrganismeControle;

        return $this;
    }

    public function getSiretEntrepriseAyantRealiseOperation(): ?string
    {
        return $this->SiretEntrepriseAyantRealiseOperation;
    }

    public function setSiretEntrepriseAyantRealiseOperation(?string $SiretEntrepriseAyantRealiseOperation): self
    {
        $this->SiretEntrepriseAyantRealiseOperation = $SiretEntrepriseAyantRealiseOperation;

        return $this;
    }

    public function getActionCorrectiveMeneeSuiteAudit(): ?string
    {
        return $this->actionCorrectiveMeneeSuiteAudit;
    }

    public function setActionCorrectiveMeneeSuiteAudit(?string $actionCorrectiveMeneeSuiteAudit): self
    {
        $this->actionCorrectiveMeneeSuiteAudit = $actionCorrectiveMeneeSuiteAudit;

        return $this;
    }

    public function getConformiteApresCorrection(): ?string
    {
        return $this->conformiteApresCorrection;
    }

    public function setConformiteApresCorrection(?string $conformiteApresCorrection): self
    {
        $this->conformiteApresCorrection = $conformiteApresCorrection;

        return $this;
    }

    public function getOperationRetireOuIssueDossierPrecedent(): ?string
    {
        return $this->operationRetireOuIssueDossierPrecedent;
    }

    public function setOperationRetireOuIssueDossierPrecedent(?string $operationRetireOuIssueDossierPrecedent): self
    {
        $this->operationRetireOuIssueDossierPrecedent = $operationRetireOuIssueDossierPrecedent;

        return $this;
    }

    public function getCommentaireGeneraux(): ?string
    {
        return $this->CommentaireGeneraux;
    }

    public function setCommentaireGeneraux(?string $CommentaireGeneraux): self
    {
        $this->CommentaireGeneraux = $CommentaireGeneraux;

        return $this;
    }

    public function getGrandPrecairePrecaireClassique(): ?string
    {
        return $this->grandPrecairePrecaireClassique;
    }

    public function setGrandPrecairePrecaireClassique(?string $grandPrecairePrecaireClassique): self
    {
        $this->grandPrecairePrecaireClassique = $grandPrecairePrecaireClassique;

        return $this;
    }

    public function getVersionCoupDePouce(): ?string
    {
        return $this->versionCoupDePouce;
    }

    public function setVersionCoupDePouce(?string $versionCoupDePouce): self
    {
        $this->versionCoupDePouce = $versionCoupDePouce;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNomDuSiteBeneficiaireOperation(): ?string
    {
        return $this->nomDuSiteBeneficiaireOperation;
    }

    public function setNomDuSiteBeneficiaireOperation(?string $nomDuSiteBeneficiaireOperation): self
    {
        $this->nomDuSiteBeneficiaireOperation = $nomDuSiteBeneficiaireOperation;

        return $this;
    }

    public function getRaisonSocialDuBeneficiaireOperation(): ?string
    {
        return $this->raisonSocialDuBeneficiaireOperation;
    }

    public function setRaisonSocialDuBeneficiaireOperation(?string $raisonSocialDuBeneficiaireOperation): self
    {
        $this->raisonSocialDuBeneficiaireOperation = $raisonSocialDuBeneficiaireOperation;

        return $this;
    }

    public function getSirenBeneficiaireOperation(): ?string
    {
        return $this->sirenBeneficiaireOperation;
    }

    public function setSirenBeneficiaireOperation(?string $sirenBeneficiaireOperation): self
    {
        $this->sirenBeneficiaireOperation = $sirenBeneficiaireOperation;

        return $this;
    }

    public function getAdresseDuSiegeSocial(): ?string
    {
        return $this->adresseDuSiegeSocial;
    }

    public function setAdresseDuSiegeSocial(?string $adresseDuSiegeSocial): self
    {
        $this->adresseDuSiegeSocial = $adresseDuSiegeSocial;

        return $this;
    }

    public function getCodepostalSiegeSocial(): ?string
    {
        return $this->codepostalSiegeSocial;
    }

    public function setCodepostalSiegeSocial(?string $codepostalSiegeSocial): self
    {
        $this->codepostalSiegeSocial = $codepostalSiegeSocial;

        return $this;
    }

    public function getVilleSiegeSocial(): ?string
    {
        return $this->villeSiegeSocial;
    }

    public function setVilleSiegeSocial(?string $villeSiegeSocial): self
    {
        $this->villeSiegeSocial = $villeSiegeSocial;

        return $this;
    }

    public function getDateAchevementOperation(): ?string
    {
        return $this->dateAchevementOperation;
    }

    public function setDateAchevementOperation(?string $dateAchevementOperation): self
    {
        $this->dateAchevementOperation = $dateAchevementOperation;

        return $this;
    }

    public function getPersonneMorale(): ?bool
    {
        return $this->personneMorale;
    }

    public function setPersonneMorale(?bool $personneMorale): self
    {
        $this->personneMorale = $personneMorale;

        return $this;
    }

    public function getNumeroLot(): ?string
    {
        return $this->numeroLot;
    }

    public function setNumeroLot(?string $numeroLot): self
    {
        $this->numeroLot = $numeroLot;

        return $this;
    }

    public function getSurfaceDeclareeDansAHFacture(): ?string
    {
        return $this->SurfaceDeclareeDansAHFacture;
    }

    public function setSurfaceDeclareeDansAHFacture(?string $SurfaceDeclareeDansAHFacture): self
    {
        $this->SurfaceDeclareeDansAHFacture = $SurfaceDeclareeDansAHFacture;

        return $this;
    }

    public function getTypeIsolantDeclare(): ?string
    {
        return $this->typeIsolantDeclare;
    }

    public function setTypeIsolantDeclare(?string $typeIsolantDeclare): self
    {
        $this->typeIsolantDeclare = $typeIsolantDeclare;

        return $this;
    }

    public function getMarqueEtReferenceIsolantDeclare(): ?string
    {
        return $this->marqueEtReferenceIsolantDeclare;
    }

    public function setMarqueEtReferenceIsolantDeclare(?string $marqueEtReferenceIsolantDeclare): self
    {
        $this->marqueEtReferenceIsolantDeclare = $marqueEtReferenceIsolantDeclare;

        return $this;
    }


}
