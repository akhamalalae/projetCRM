<?php

namespace App\Repository;

use App\Entity\RequeteTableauBord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequeteTableauBord|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequeteTableauBord|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequeteTableauBord[]    findAll()
 * @method RequeteTableauBord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequeteTableauBordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequeteTableauBord::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RequeteTableauBord $entity, bool $flush = true): void
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
    public function remove(RequeteTableauBord $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return RequeteTableauBord[] Returns an array of RequeteTableauBord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequeteTableauBord
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
