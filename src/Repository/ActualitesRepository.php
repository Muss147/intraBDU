<?php

namespace App\Repository;

use App\Entity\Actualites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actualites>
 */
class ActualitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actualites::class);
    }

       /**
        * @return Actualites[] Returns an array of Actualites objects
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

       public function findAllOnline($nombre = 20): array
       {
           return $this->createQueryBuilder('a')
                ->andWhere('a.deletedAt IS NULL')
               ->andWhere('a.online = :val')
               ->setParameter('val', true)
                ->orderBy('a.createdAt', 'DESC')
                ->setMaxResults($nombre)
               ->getQuery()
               ->getResult()
           ;
       }
    //    public function findOneBySomeField($value): ?Actualites
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
