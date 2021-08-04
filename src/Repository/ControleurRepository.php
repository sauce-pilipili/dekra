<?php

namespace App\Repository;

use App\Entity\Controleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Controleur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Controleur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Controleur[]    findAll()
 * @method Controleur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Controleur::class);
    }

    /**
     * @return Controleur[] retourne le resultat de la recherche page controleur acces par admin national
     */

    public function findControleurByData($dep, $spe)
    {
        $qb = $this->createQueryBuilder('c');

        if ($dep != null && $dep != '') {
            $qb->join('c.departement', 'd')
                ->andWhere('d.id = :val1')
                ->setParameter('val1', $dep);
        }
        if ($spe != null && $spe != '') {
            $qb->join('c.specialite', 's')
                ->andWhere('s.id =:val2')
                ->setParameter('val2', $spe);
        }
        return $qb->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Controleur
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
