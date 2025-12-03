<?php

namespace App\Repository;

use App\Entity\Files;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Files>
 */
class FilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Files::class);
    }

       /**
        * @return Files[] Returns an array of Files objects
        */
       public function findAll(): array
       {
           return $this->createQueryBuilder('f')
                ->andWhere('f.deletedAt IS NULL')
                ->orderBy('f.id', 'ASC')
                ->getQuery()
                ->getResult()
           ;
       }

       public function findAllBy($value): array
       {
           return $this->createQueryBuilder('f')
               ->andWhere('f.type = :val')
               ->setParameter('val', $value)
                ->andWhere('f.deletedAt IS NULL')
                ->orderBy('f.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }
}
