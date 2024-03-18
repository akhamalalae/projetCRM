<?php

namespace App\Repository;

use App\Entity\TableauBordFiltresConditions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableauBordFiltresConditions|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauBordFiltresConditions|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauBordFiltresConditions[]    findAll()
 * @method TableauBordFiltresConditions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauBordFiltresConditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauBordFiltresConditions::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TableauBordFiltresConditions $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TableauBordFiltresConditions $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TableauBordFiltresConditions[] Returns an array of TableauBordFiltresConditions objects
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
    public function findOneBySomeField($value): ?TableauBordFiltresConditions
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
