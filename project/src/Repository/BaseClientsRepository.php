<?php

namespace App\Repository;

use App\Entity\BaseClients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseClients|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseClients|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseClients[]    findAll()
 * @method BaseClients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseClients::class);
    }

    // /**
    //  * @return BaseClients[] Returns an array of BaseClients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BaseClients
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
