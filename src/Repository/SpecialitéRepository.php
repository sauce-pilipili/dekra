<?php

namespace App\Repository;

use App\Entity\Specialité;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specialité|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialité|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialité[]    findAll()
 * @method Specialité[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialitéRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialité::class);
    }

    // /**
    //  * @return Specialité[] Returns an array of Specialité objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specialité
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
