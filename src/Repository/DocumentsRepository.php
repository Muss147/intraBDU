<?php

namespace App\Repository;

use App\Entity\Documents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Documents>
 */
class DocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documents::class);
    }

       /**
        * @return Documents[] Returns an array of Documents objects
        */
       public function findByType($value): array
       {
           return $this->createQueryBuilder('d')
                ->andWhere('d.parent IS NULL')
               ->andWhere('d.type = :val')
               ->setParameter('val', $value)
                ->andWhere('d.deletedAt IS NULL')
                ->orderBy('d.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function findAllDossier($value): array
       {
           return $this->createQueryBuilder('d')
                ->andWhere('d.source IS NULL')
               ->andWhere('d.type = :val')
               ->setParameter('val', $value)
                ->andWhere('d.deletedAt IS NULL')
                ->orderBy('d.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function moreViewed(): array
       {
           return $this->createQueryBuilder('d')
                ->andWhere('d.deletedAt IS NULL')
                ->andWhere('d.source IS NOT NULL')
               ->andWhere('d.type = :val')
               ->setParameter('val', 'procedures')
                ->orderBy('d.vues', 'DESC')
                ->setMaxResults(9)
               ->getQuery()
               ->getResult()
           ;
       }
    //    public function findOneBySomeField($value): ?Documents
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
