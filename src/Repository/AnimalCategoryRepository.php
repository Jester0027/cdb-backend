<?php

namespace App\Repository;

use App\Entity\AnimalCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AnimalCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalCategory[]    findAll()
 * @method AnimalCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalCategory::class);
    }

    // /**
    //  * @return AnimalCategory[] Returns an array of AnimalCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnimalCategory
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
