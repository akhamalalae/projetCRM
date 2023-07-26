<?php

namespace App\Repository;

use App\Entity\RenderVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RenderVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenderVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenderVous[]    findAll()
 * @method RenderVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenderVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RenderVous::class);
    }

    public function findCalendarRendezVous($user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT r FROM App\Entity\RenderVous r
                WHERE (r.effectuer != TRUE OR r.effectuer IS NULL)
                AND (r.userCreateur = ".$user." OR r.intervenant = ".$user.")
            "
        );

        return $query->getResult();
    }

    public function getListRendezVousToAchieve($dateNow, $user)
    {
        $resultats = $this->createQueryBuilder('r')
            ->andWhere('r.start >= :dateNow')
            ->andWhere('r.effectuer != TRUE OR r.effectuer IS NULL')
            ->andWhere('r.intervenant = :user OR r.userCreateur = :user')
            ->setParameter('dateNow', $dateNow->format('Y-m-d'))
            ->setParameter('user', $user)
            ->orderBy('r.start', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return $resultats;
    }

    public function findRendezVousByIntervenentBetweenTowDate($dateDebut, $dateFin, $intervenant)
    {
        $resultats = $this->createQueryBuilder('r')
            ->andWhere('r.start >= :dateDebut')
            ->andWhere('r.end <= :dateFin')
            ->andWhere('r.intervenant = :intervenant')
            ->orderBy('r.start', 'ASC')
            ->setParameter('dateDebut', $dateDebut->format('Y-m-d H:i:s'))
            ->setParameter('dateFin', $dateFin->format('Y-m-d H:i:s'))
            ->setParameter('intervenant', $intervenant)
            ->getQuery()
            ->getResult()
        ;
        return $resultats;
    }

    public function findRealizeRendezVous($user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT r FROM App\Entity\RenderVous r
                WHERE (r.effectuer = TRUE)
                AND (r.userCreateur = ".$user." OR r.intervenant = ".$user.")
            "
        );

        return $query->getResult();
    }

    public function agendaFiltres($intervenants, $entreprises, $formulaire, $pointeVente)
    {
        return $this->createQueryBuilder('r')
            ->andwhere('r.intervenant IN (:intervenants)')
            ->andwhere('r.entreprise IN (:entreprises)')
            ->andwhere('r.formulaire IN (:formulaire)')
            ->andwhere('r.pointeVente IN (:pointeVente)')
            ->setParameter('intervenants', $intervenants)
            ->setParameter('entreprises', $entreprises)
            ->setParameter('formulaire', $formulaire)
            ->setParameter('pointeVente', $pointeVente)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countRendezVous($dateNow)
    {
        $resultats = $this->createQueryBuilder('r')
            ->andWhere('r.start >= :dateNow')
            ->setParameter('dateNow', $dateNow->format('Y-m-d'))
            ->select('count(r.id)')
            ->orderBy('r.start', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();
        ;
        return $resultats;
    }
}
