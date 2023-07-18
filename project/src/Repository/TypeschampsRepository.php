<?php

namespace App\Repository;

use App\Entity\Typeschamps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Typeschamps|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeschamps|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeschamps[]    findAll()
 * @method Typeschamps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeschampsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeschamps::class);
    }

    // /**
    //  * @return Typeschamps[] Returns an array of Typeschamps objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Typeschamps
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
