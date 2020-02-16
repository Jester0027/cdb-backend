<?php

namespace App\Repository;

use App\Entity\EventTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventTheme|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventTheme|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventTheme[]    findAll()
 * @method EventTheme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventTheme::class);
    }

    // /**
    //  * @return EventTheme[] Returns an array of EventTheme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventTheme
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
