<?php

namespace App\Repository;

use App\Entity\ChampsFormulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChampsFormulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChampsFormulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChampsFormulaire[]    findAll()
 * @method ChampsFormulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampsFormulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChampsFormulaire::class);
    }

    // /**
    //  * @return ChampsFormulaire[] Returns an array of ChampsFormulaire objects
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
    public function findOneBySomeField($value): ?ChampsFormulaire
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
