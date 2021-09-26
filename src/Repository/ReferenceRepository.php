<?php

namespace App\Repository;

use App\Entity\Reference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reference[]    findAll()
 * @method Reference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reference::class);
    }

    // /**
    //  * @return Reference[] Returns an array of Reference objects
    //  */

    public function findRef()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.complet = :val')
            ->setParameter('val', 0)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneByReference($value): ?Reference
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.reference = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
