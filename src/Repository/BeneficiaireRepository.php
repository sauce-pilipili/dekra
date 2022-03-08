<?php

namespace App\Repository;

use App\Entity\Beneficiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beneficiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beneficiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beneficiaire[]    findAll()
 * @method Beneficiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeneficiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beneficiaire::class);
    }

//    ******************************valide pour v2******************************************

    /**
     * @return Beneficiaire |null
     */
    public function show($id)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c', 'd')
            ->join('b.client', 'c')
            ->join('b.departement', 'd')
            ->andWhere('b.id =:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();


    }

    /**
     * //  * @return Beneficiaire[] Returns un tableau liste des beneficiaire
     * si un admin regional consulte les benef de ses client
     * //  */
    public function ClientListAdmin()
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'u', 'd')
            ->join('b.client', 'u')
            ->join('b.departement', 'd')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * //  * @return Beneficiaire[] Returns un tableau avec la liste de ses client actuel
     * //  */
    public function findClientList($value)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c')
            ->join('b.client', 'c')
            ->where('b.client = :value')
            ->setParameter('value', $value)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Beneficiaire[] Returns un tableau pour le call center avec les client de la recherche
     * utilisable en ajax
     */

    public function findByName($value)
    {
        return $this->createQueryBuilder('b')
            ->Where('b.name LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Beneficiaire[] Returns un tableau avec la recherhce trié par id client
     */
    public function findBySearch($user, $value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.client = :user')
            ->setParameter('user', $user->getId())
            ->andWhere('b.name LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findRefficheOp($emmy = null)
    {
        $query = $this->createQueryBuilder('b')
            ->select('b.referenceFicheOperation');
        if ($emmy != null) {
            $query->where('b.ReferenceEmmyDemande = :val')
                ->setParameter('val', $emmy);
        }
        $query->distinct();
        return $query->getQuery()->getResult();
    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findPrecarite($emmy = null)
    {
        return $this->createQueryBuilder('b')
            ->select('b.grandPrecairePrecaireClassique')
            ->distinct()
            ->getQuery()
            ->getResult();
    }


    /**
     * @return integer
     */
    public function nombreBeneficiaireDetail($ref, $fiche, $precarite)
    {
        return $this->createQueryBuilder('b')
            ->select('count(b)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref)
            ->andwhere('b.referenceFicheOperation = :fiche')
            ->setParameter('fiche', $fiche)
            ->andwhere('b.grandPrecairePrecaireClassique = :precarite')
            ->setParameter('precarite', $precarite)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @return integer
     */
    public function nombreBeneficiaireDetailrdv($ref, $fiche, $precarite)
    {
        return $this->createQueryBuilder('b')
            ->select('count(b)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref)
            ->andwhere('b.referenceFicheOperation = :fiche')
            ->setParameter('fiche', $fiche)
            ->andwhere('b.grandPrecairePrecaireClassique = :precarite')
            ->setParameter('precarite', $precarite)
            ->andWhere('b.rdv IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findForRdvOrdercount($emmy, $refOperation, $precarite)
    {

        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.ReferenceEmmyDemande = :lot')
            ->setParameter('lot', $emmy)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :precarite')
            ->setParameter('precarite', $precarite)
            ->andWhere('b.referenceFicheOperation = :ope')
            ->setParameter('ope', $refOperation)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return integer Returns an array of Beneficiaire objects
     */
    public function findwhereRDV($emmy, $refOperation, $precarite)
    {

        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.ReferenceEmmyDemande = :emmy')
            ->setParameter('emmy', $emmy)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $precarite)
            ->andWhere('b.referenceFicheOperation = :ope')
            ->setParameter('ope', $refOperation)
            ->andWhere('b.rdv IS NOT null')
            ->getQuery()
            ->getSingleScalarResult();

    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findListOfBeneficiaireToCall($ref, $cdp, $preca, $ope, $order = null, $orderDirection = null)
    {

        $qb = $this->createQueryBuilder('b')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref)
            ->andWhere('b.versionCoupDePouce = :cdp')
            ->setParameter('cdp', $cdp)
            ->andWhere('b.referenceFicheOperation = :ope')
            ->setParameter('ope', $ope)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $preca);

        if ($order != null && $orderDirection != null) {
            switch ($order) {
                case 'name':
                    $order = 'b.name';
                    break;
                case 'postal':
                    $order = 'b.codePostal';
                    break;
                case 'ville':
                    $order = 'b.ville';
                    break;

            }
            $qb->orderBy($order, $orderDirection);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
//************************************** requete v2**********************************************

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findCoupDePouce($emmy)
    {
        return $this->createQueryBuilder('b')
            ->select('b.versionCoupDePouce')
            ->andWhere('b.ReferenceEmmyDemande LIKE :emmy')
            ->setParameter('emmy', "%" . $emmy . "%")
            ->andWhere('b.versionCoupDePouce IS NOT NULL')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findBarBatSiCDPNull($emmy = null)
    {
        return $this->createQueryBuilder('b')
            ->select('b.referenceFicheOperation')
            ->andWhere('b.ReferenceEmmyDemande = :emmy')
            ->setParameter('emmy', $emmy)
            ->andWhere('b.versionCoupDePouce IS NULL')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return integer
     */
    public function nombreDossierDepose($emmy, $version = null, $fiche = null, $preca = null)
    {

        $qb = $this->createQueryBuilder('b')
            ->select('count(b)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $emmy);
        if ($version) {
            $qb->andwhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $version);
        }
        if ($fiche) {
            $qb->andwhere('b.referenceFicheOperation = :fiche')
                ->setParameter('fiche', $fiche);
        }
        if ($preca) {
            $qb->andwhere('b.grandPrecairePrecaireClassique = :preca')
                ->setParameter('preca', $preca);
        }
        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @return integer Returns an array of Beneficiaire objects
     */
    public function findCallCenterRDV($emmy, $version= null, $refOperation = null, $precarite= null)
    {

        $qb = $this->createQueryBuilder('b')
            ->select('count(b)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->andWhere('b.rdv IS NOT NULL')
            ->setParameter('ref', $emmy);
        if ($version) {
            $qb->andwhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $version);
        }
        if ($refOperation) {
            $qb->andwhere('b.referenceFicheOperation = :fiche')
                ->setParameter('fiche', $refOperation);
        }
        if ($precarite) {
            $qb->andwhere('b.grandPrecairePrecaireClassique = :preca')
                ->setParameter('preca', $precarite);
        }
        return $qb
            ->getQuery()
            ->getSingleScalarResult();


    }


    public function findListOfBeneficiaireSearch($ref, $cdp, $preca, $ope, $search)
    {

        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref)
            ->andwhere('b.versionCoupDePouce = :cdp')
            ->setParameter('cdp', $cdp)
            ->andwhere('b.referenceFicheOperation = :fiche')
            ->setParameter('fiche', $ope)
            ->andwhere('b.grandPrecairePrecaireClassique = :preca')
            ->setParameter('preca', $preca)
            ->andWhere('b.name LIKE :search')
            ->setParameter('search', "%" . $search . "%");
        return $qb
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();


    }

    /**
     * @return integer
     */
    public function nombreSatisfaisant($ref, $version = null, $fiche = null, $preca = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('count(b)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref);
        if ($version) {
            $qb->andwhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $version);
        }
        if ($fiche) {
            $qb->andwhere('b.referenceFicheOperation = :fiche')
                ->setParameter('fiche', $fiche);
        }
        if ($preca) {
            $qb->andwhere('b.grandPrecairePrecaireClassique = :preca')
                ->setParameter('preca', $preca);
        }

        $qb->andWhere("b.satisfaisant103 = :satisfaisant OR b.satisfaisant102 = :satisfaisant OR b.satisfaisant101 = :satisfaisant")
            ->setParameter('satisfaisant', "satisfaisant");

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findByCDPDossier($ref, $cdp = null, $ope = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.referenceFicheOperation')
//reference emmy en cours de recherche
            ->Where('b.ReferenceEmmyDemande =:ref')
            ->setParameter('ref', $ref);
        if ($cdp) {
            $qb->andWhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $cdp);
        }
        if ($ope) {
            $qb->andWhere('b.referenceFicheOperation =:ope')
                ->setParameter('ope', $ope);
        }
        return $qb->distinct()
            ->distinct()
            ->getQuery()
            ->getResult();
    }


    public function findByPrecaritéByCDPDossier($ref, $cdp = null, $ope = null, $result = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.grandPrecairePrecaireClassique')
            ->Where('b.ReferenceEmmyDemande =:ref')
            ->setParameter('ref', $ref);
        if ($cdp) {
            $qb->andWhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $cdp);
        }
        if (!$cdp) {
            $qb->andWhere('b.versionCoupDePouce is null');
        }

        if ($ope) {
            $qb->andWhere('b.referenceFicheOperation =:result')
                ->setParameter('result', $ope);
        }

        return $qb
            ->distinct()
            ->getQuery()
            ->getResult();
    }


    /**
     * @return integer Returns an array of Beneficiaire objects
     */
    public function findwhereRDVbyCdp($emmy, $cdp, $refOperation)
    {

        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.ReferenceEmmyDemande = :emmy')
            ->setParameter('emmy', $emmy)
            ->andWhere('b.versionCoupDePouce LIKE :val')
            ->setParameter('val', $cdp)
            ->andWhere('b.referenceFicheOperation = :ope')
            ->setParameter('ope', $refOperation)
            ->andWhere('b.rdv IS NOT null')
            ->getQuery()
            ->getSingleScalarResult();

    }


    /**
     * @return integer
     */
    public function findlistBycriteria($ref, $version = null, $fiche = null, $preca = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref);
        if ($version) {
            $qb->andwhere('b.versionCoupDePouce = :version')
                ->setParameter('version', $version);
        }
        if ($fiche) {
            $qb->andwhere('b.referenceFicheOperation = :fiche')
                ->setParameter('fiche', $fiche);
        }
        if ($preca) {
            $qb->andwhere('b.grandPrecairePrecaireClassique = :preca')
                ->setParameter('preca', $preca);
        }
        return $qb
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

//***************************************non defini*********************************************

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findRef()
    {
        return $this->createQueryBuilder('b')
            ->select('b.ReferenceEmmyDemande')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */

    public function findClientID($value)
    {
        return $this->createQueryBuilder('b')
            ->Where('b.client =  :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

}
