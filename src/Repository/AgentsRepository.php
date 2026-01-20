<?php

namespace App\Repository;

use App\Entity\Agents;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Agents>
 */
class AgentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agents::class);
    }

    /**
    * @return Agents[] Returns an array of Agents objects
    */
    public function findAll(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.deletedAt IS NULL')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFilteredQuery(string $term = ''): Query
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.service', 's')
            ->leftJoin('s.parent', 'p')
            ->andWhere('a.deletedAt IS NULL')
            ->andWhere('a.nom LIKE :term OR 
                a.prenoms LIKE :term OR 
                a.email LIKE :term OR 
                a.fonction LIKE :term OR 
                a.fixe LIKE :term OR 
                a.telephone LIKE :term OR 
                s.libelle LIKE :term OR 
                p.libelle LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery();
    }
    
    public function findVotantByTerms(string $term = ''): ?Agents
    {
        return $this->createQueryBuilder('a')
            ->where('a.matricule LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function findAnniversairesDuMois(): array
    {
        $moisActuel = (new \DateTime())->format('m');

        return $this->createQueryBuilder('a')
            ->where('MONTH(a.anniversaire) = :mois')
            ->setParameter('mois', $moisActuel)
            ->getQuery()
            ->getResult();
    }
    
    public function findAkwabaDuMois(): array
    {
        $now = new \DateTime();
        $moisActuel = $now->format('m');
        $anneeActuelle = $now->format('Y');

        return $this->createQueryBuilder('a')
            ->where('MONTH(a.createdAt) = :mois')
            ->andWhere('YEAR(a.createdAt) = :annee')
            ->setParameter('mois', $moisActuel)
            ->setParameter('annee', $anneeActuelle)
            ->getQuery()
            ->getResult();
    }

    public function getClassement(): array
    {
        $now = new \DateTime();
        $moisActuel = $now->format('m');

        return $this->createQueryBuilder('a')
            ->select('a as agent, SUM(n.note) AS totalPoints')
            ->leftJoin('a.votesRecus', 'v')
            ->leftJoin('v.notes', 'n')
            ->where('MONTH(v.votedAt) = :mois')
            ->setParameter('mois', $moisActuel)
            ->groupBy('a.id')
            ->orderBy('totalPoints', 'DESC')
            ->getQuery()
            ->getResult();
    }
        
    public function countNewUsersLast30Days(): array
    {
        $date = new \DateTime('-30 days');
        return $this->createQueryBuilder('a')
            // ->select('COUNT(a.id)')
            ->andWhere('a.createdAt >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
            // ->getSingleScalarResult()
        ;
    }
}
