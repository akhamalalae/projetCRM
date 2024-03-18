<?php

namespace App\Repository;

use App\Entity\Entities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entities[]    findAll()
 * @method Entities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entities::class);
    }

    // /**
    //  * @return Entities[] Returns an array of Entities objects
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
    public function findOneBySomeField($value): ?Entities
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
