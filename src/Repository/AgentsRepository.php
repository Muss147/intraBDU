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
                ->andWhere('a.nom LIKE :term OR a.prenoms LIKE :term OR a.email LIKE :term OR a.fonction LIKE :term OR s.libelle LIKE :term OR p.libelle LIKE :term')
                ->setParameter('term', '%' . $term . '%')
                ->getQuery();
        }
        
       public function findVotantByTerms(string $term = ''): ?Agents
        {
            return $this->createQueryBuilder('a')
                ->andWhere('a.nom LIKE :term OR a.prenoms LIKE :term OR a.matricule LIKE :term')
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

    //    public function findOneBySomeField($value): ?Agents
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
