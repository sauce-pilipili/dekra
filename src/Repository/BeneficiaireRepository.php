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
     * @return integer Returns an array of Beneficiaire objects
     */
    public function pourcentageEmmyTotal($ref)
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.ReferenceEmmyDemande)')
            ->where('b.ReferenceEmmyDemande = :ref')
            ->setParameter('ref', $ref)
//            ->andWhere('b.rdv IS NOT null')
            ->getQuery()
            ->getSingleScalarResult();
    }



    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
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
    public function findForRdvOrder($lot, $precarite,$statut,$id)
    {

        return $this->createQueryBuilder('b')

            ->where('b.numeroLot = :lot')
            ->setParameter('lot', $lot)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $precarite )
            ->andWhere('b.personneMorale = :statut')
            ->setParameter('statut', $statut)
            ->andWhere('b.client = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
     */
    public function findForRdvOrdercount($lot, $precarite,$statut,$id)
    {

        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.numeroLot = :lot')
            ->setParameter('lot', $lot)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $precarite )
            ->andWhere('b.personneMorale = :statut')
            ->setParameter('statut', $statut)
            ->andWhere('b.client = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * @return integer Returns an array of Beneficiaire objects
     */
    public function findwhereRDV($lot, $precarite,$statut,$id)
    {

        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.numeroLot = :lot')
            ->setParameter('lot', $lot)
            ->andWhere('b.grandPrecairePrecaireClassique LIKE :val')
            ->setParameter('val', $precarite )
            ->andWhere('b.personneMorale = :statut')
            ->setParameter('statut', $statut)
            ->andWhere('b.client = :id')
            ->setParameter('id',$id)
            ->andWhere('b.rdv IS NOT null')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Beneficiaire |null
     */
    public function show($id){
        return $this->createQueryBuilder('b')
            ->select('b','c','d')
            ->join('b.client', 'c')
            ->join('b.departement','d')
            ->andWhere('b.id =:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();



    }

    /**
     * @return Beneficiaire[] Returns an array of Beneficiaire objects
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

    /**
     * //  * @return Beneficiaire[] Returns an array of Beneficiaire objects
     * //  */
    public function findClientList($value)
    {
        return $this->createQueryBuilder('b')
            ->select('b','c')
            ->join('b.client','c')
            ->where('b.client = :value')
            ->setParameter('value', $value)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * //  * @return Beneficiaire[] Returns an array of Beneficiaire objects
     * //  */
    public function ClientListAdmin()
    {
        return $this->createQueryBuilder('b')
            ->select('b','u','d')
            ->join('b.client','u')
            ->join('b.departement','d')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
