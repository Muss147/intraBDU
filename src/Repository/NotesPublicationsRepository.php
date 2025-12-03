<?php

namespace App\Repository;

use App\Entity\NotesPublications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotesPublications>
 */
class NotesPublicationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotesPublications::class);
    }

       /**
        * @return NotesPublications[] Returns an array of NotesPublications objects
        */
       public function findAll(): array
       {
           return $this->createQueryBuilder('n')
                ->andWhere('n.deletedAt IS NULL')
               ->orderBy('n.id', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function findAllOnline($nombre = 20): array
       {
           return $this->createQueryBuilder('n')
                ->andWhere('n.deletedAt IS NULL')
               ->andWhere('n.online = :val')
               ->setParameter('val', true)
                ->orderBy('n.createdAt', 'DESC')
                ->setMaxResults($nombre)
               ->getQuery()
               ->getResult()
           ;
       }
}
