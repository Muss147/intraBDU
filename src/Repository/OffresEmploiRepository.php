<?php

namespace App\Repository;

use App\Entity\OffresEmploi;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffresEmploi>
 */
class OffresEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffresEmploi::class);
    }

       /**
        * @return OffresEmploi[] Returns an array of OffresEmploi objects
        */
       public function findAll(): array
       {
           return $this->createQueryBuilder('o')
               ->andWhere('o.deletedAt IS NULL')
               ->orderBy('o.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function getFilteredQuery(array $search = [], array $filters = []): Query
        {
            $qb = $this->createQueryBuilder('o')
                ->leftJoin('o.metier', 'm')
                ->leftJoin('o.direction', 'd')
                ->leftJoin('d.parent', 'a')
                ->andWhere('o.deletedAt IS NULL')

                // ✅ Offres encore actives
                ->andWhere('o.dateExpiration > :today')

                // Recherche texte
                ->andWhere(
                    'o.titre LIKE :term1 
                    OR o.experience LIKE :term1 
                    OR o.niveauPoste LIKE :term1 
                    OR o.niveauFormation LIKE :term1 
                    OR m.libelle LIKE :term1'
                )
                ->andWhere('d.libelle LIKE :term2 OR a.libelle LIKE :term2')

                ->setParameter('term1', '%' . ($search['keywords'] ?? '') . '%')
                ->setParameter('term2', '%' . ($search['lieu'] ?? '') . '%')
                ->setParameter('today', new \DateTimeImmutable('today'));

            // ✅ Filtres dynamiques
            if (!empty($filters['metier'])) {
                $qb->andWhere('m.id = :metier')
                ->setParameter('metier', $filters['metier']);
            }

            if (!empty($filters['experience'])) {
                $qb->andWhere('o.experience = :experience')
                ->setParameter('experience', $filters['experience']);
            }

            if (!empty($filters['niveau_poste'])) {
                $qb->andWhere('o.niveauPoste = :niveau_poste')
                ->setParameter('niveau_poste', $filters['niveau_poste']);
            }

            if (!empty($filters['niveau_formation'])) {
                $qb->andWhere('o.niveauFormation = :niveau_formation')
                ->setParameter('niveau_formation', $filters['niveau_formation']);
            }

            return $qb->getQuery();
        }
        
    //    public function findOneBySomeField($value): ?OffresEmploi
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
