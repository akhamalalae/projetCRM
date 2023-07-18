<?php

namespace App\Repository;

use App\Entity\MenuSousCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuSousCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuSousCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuSousCategorie[]    findAll()
 * @method MenuSousCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuSousCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuSousCategorie::class);
    }

    // /**
    //  * @return MenuSousCategorie[] Returns an array of MenuSousCategorie objects
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
    public function findOneBySomeField($value): ?MenuSousCategorie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getMenuSousCategorie()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.statut = 1')
            ->orderBy('m.ordre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
