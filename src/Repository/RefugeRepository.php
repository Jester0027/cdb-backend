<?php

namespace App\Repository;

use App\Entity\Refuge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Refuge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Refuge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Refuge[]    findAll()
 * @method Refuge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefugeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Refuge::class);
    }

    public function search($limit, $page)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r')
        ;

        return $this->paginate($qb, $limit, $page);
    }

    // /**
    //  * @return Refuge[] Returns an array of Refuge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Refuge
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
