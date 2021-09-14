<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function findClient($value)
    {
        return $this->createQueryBuilder('u')
            ->join('u.AdminID', 'admin')
            ->where('admin.id = :value')
            ->setParameter('value', $value->getID())
            ->andWhere('u.roles like :val')
            ->setParameter('val', '%"'.'ROLE_CLIENT'.'"%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function findClientForCallCenter()
    {
        return $this->createQueryBuilder('u')
            ->Where('u.roles like :val')
            ->setParameter('val', '%"'.'ROLE_CLIENT'.'"%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function findClientInAJax($value,$data)
    {
        return $this->createQueryBuilder('u')
            ->join('u.AdminID', 'admin')
            ->where('admin.id = :value')
            ->setParameter('value', $value->getID())
            ->andWhere('u.roles like :val')
            ->setParameter('val', '%"'.'ROLE_CLIENT'.'"%')
            ->andWhere('u.name LIKE :data')
            ->setParameter('data','%'.$data.'%')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function findClientSuperAdmin($data)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles like :val')
            ->setParameter('val', '%"'.'ROLE_CLIENT'.'"%')
            ->andWhere('u.name LIKE :data')
            ->setParameter('data','%'.$data.'%')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function finClientBySuperAdmin()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles like :val')
            ->setParameter('val', '%"'.'ROLE_CLIENT'.'"%')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
      * @return User[] Returns an array of User objects
      */
    public function findAllcomplete()
    {
        return $this->createQueryBuilder('u')
            ->leftjoin('u.region','r')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     //* @return User[] Returns an array of User objects
     */

    public function findBySearch($data){
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :val')
            ->setParameter('val','%'.$data.'%');
        return $qb->getQuery()->getResult();
    }



    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
