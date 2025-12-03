<?php

namespace App\Repository;

use App\Entity\Parametres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parametres>
 */
class ParametresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parametres::class);
    }

       /**
        * @return Parametres[] Returns an array of Parametres objects
        */
       public function findByType($value): array
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.type = :val')
               ->setParameter('val', $value)
                ->andWhere('p.deletedAt IS NULL')
                ->orderBy('p.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Parametres
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
