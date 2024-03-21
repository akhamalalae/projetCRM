<?php

namespace App\Repository;

use App\Entity\Formulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formulaire[]    findAll()
 * @method Formulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formulaire::class);
    }

    // /**
    //  * @return Formulaire[] Returns an array of Formulaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
                $qb = $this->createQueryBuilder('f');
                $qb->where('1=1');
                if ($id_filtre_formulaires) {
                    $qb->where('f.id IN  (' . implode(",", $id_filtre_formulaires) . ')');
                }
                $query = $qb
                    ->select($fields)
                    //->andWhere('f.exampleField = :val')
                    ->setMaxResults(10)
                    ->getQuery()
                ;

                return $query->getResult();
    }
    */

    public function getResultRequete($requete)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery($requete)
                             ->setMaxResults(10000)
                             ->getResult();
    }
}
