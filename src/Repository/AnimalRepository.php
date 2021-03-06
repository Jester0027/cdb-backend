<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function search($limit, $page)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
        ;

        return $this->paginate($qb, $limit, $page);
    }

    public function searchFromCategory($category, $limit, $page)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.animalCategory = :category')
            ->setParameter('category', $category)
        ;

        return $this->paginate($qb, $limit, $page);
    }

    public function searchFromRefuge($refuge, $limit, $page)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.refuge = :refuge')
            ->setParameter('refuge', $refuge)
        ;

        return $this->paginate($qb, $limit, $page);
    }

    // /**
    //  * @return Animal[] Returns an array of Animal objects
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
    public function findOneBySomeField($value): ?Animal
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
