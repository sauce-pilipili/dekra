<?php

namespace App\Repository;

use App\Entity\Departements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Departements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departements[]    findAll()
 * @method Departements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departements::class);
    }
//    **********************valide pour v2 ********************************
    public function findBySearch($data){
        $qb = $this->createQueryBuilder('d')
            ->andWhere('d.name LIKE :val')
            ->setParameter('val','%'.$data.'%');
        return $qb->getQuery()->getResult();
    }

//    ***************non defini***************************************
    public function DepartmentClient($value): ?Departements
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.numero = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
