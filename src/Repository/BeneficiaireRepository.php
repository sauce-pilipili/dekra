<?php

namespace App\Repository;

use App\Entity\Beneficiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * //  * @return Beneficiaire[] Returns an array of Beneficiaire objects
     * //  */
    public function findClientList($value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.client = :value')
            ->setParameter('value', $value->getID())
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
