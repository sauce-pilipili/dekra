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
