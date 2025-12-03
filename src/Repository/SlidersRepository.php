<?php

namespace App\Repository;

use App\Entity\Sliders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sliders>
 */
class SlidersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sliders::class);
    }

       /**
        * @return Sliders[] Returns an array of Sliders objects
        */
       public function findAll(): array
       {
           return $this->createQueryBuilder('s')
                ->andWhere('s.deletedAt IS NULL')
                ->orderBy('s.id', 'ASC')
                ->getQuery()
                ->getResult()
           ;
       }

       public function findAllOnline(): array
       {
           return $this->createQueryBuilder('s')
                ->andWhere('s.deletedAt IS NULL')
               ->andWhere('s.online = :val')
               ->setParameter('val', true)
               ->getQuery()
               ->getResult()
           ;
       }
}
