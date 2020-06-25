<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function search($limit, $page)
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.eventDate')
        ;

        return $this->paginate($qb, $limit, $page);
    }

    public function searchFromTheme($theme, $limit, $page)
    {
        $qb = $this->createQueryBuilder('e')
            ->where("e.eventTheme = :theme")
            ->setParameter("theme", $theme)
            ->orderBy('e.eventDate')
        ;

        return $this->paginate($qb, $limit, $page);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
