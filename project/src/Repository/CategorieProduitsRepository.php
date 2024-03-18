<?php

namespace App\Repository;

use App\Entity\CategorieProduits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieProduits|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieProduits|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieProduits[]    findAll()
 * @method CategorieProduits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieProduits::class);
    }

    // /**
    //  * @return CategorieProduits[] Returns an array of CategorieProduits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieProduits
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
