<?php

namespace App\Repository;

use App\Entity\TableauBordFiltresOperators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableauBordFiltresOperators|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauBordFiltresOperators|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauBordFiltresOperators[]    findAll()
 * @method TableauBordFiltresOperators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauBordFiltresOperatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauBordFiltresOperators::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TableauBordFiltresOperators $entity, bool $flush = true): void
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
    public function remove(TableauBordFiltresOperators $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function getOperators($arrayOperators)
    {
        return $this->createQueryBuilder('t')
            ->where('t.id IN (:arrayOperators)')
            ->setParameter('arrayOperators', $arrayOperators)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?TableauBordFiltresOperators
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
