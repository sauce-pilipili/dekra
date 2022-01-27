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
    public function findListOfBeneficiaireToCall($emmy, $precarite, $refoperation, $order = null, $orderDirection = null)
    {

        $qb = $this->createQueryBuilder('b')
            ->where('b.ReferenceEmmyDemande = :emmy')
            ->setParameter('emmy', $emmy)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $precarite)
            ->andWhere('b.referenceFicheOperation = :ref')
            ->setParameter('ref', $refoperation);
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
            $qb->orderBy($order,$orderDirection);
        }

        return $qb
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
