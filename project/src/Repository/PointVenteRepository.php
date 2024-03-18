<?php

namespace App\Repository;

use App\Entity\PointVente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PointVente|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointVente|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointVente[]    findAll()
 * @method PointVente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointVenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointVente::class);
    }

    // /**
    //  * @return PointVente[] Returns an array of PointVente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PointVente
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
