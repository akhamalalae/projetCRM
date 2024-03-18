<?php

namespace App\Repository;

use App\Entity\MenuCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCategorie[]    findAll()
 * @method MenuCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuCategorie::class);
    }

    // /**
    //  * @return MenuCategorie[] Returns an array of MenuCategorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuCategorie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getMenu()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.statut = 1')
            ->orderBy('m.ordre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
