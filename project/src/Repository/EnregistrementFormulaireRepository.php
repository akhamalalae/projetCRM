<?php

namespace App\Repository;

use App\Entity\EnregistrementFormulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnregistrementFormulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnregistrementFormulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnregistrementFormulaire[]    findAll()
 * @method EnregistrementFormulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnregistrementFormulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnregistrementFormulaire::class);
    }

    // /**
    //  * @return EnregistrementFormulaire[] Returns an array of EnregistrementFormulaire objects
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
    public function findOneBySomeField($value): ?EnregistrementFormulaire
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
