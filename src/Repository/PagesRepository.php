<?php

namespace App\Repository;

use App\Entity\Pages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pages>
 */
class PagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pages::class);
    }

    /**
    * @return Pages[] Returns an array of Pages objects
    */
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.statut = :val')
            ->setParameter('val', 1)
            ->andWhere('p.deletedAt IS NULL')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Pages[] Returns an array of Pages objects with file name
     */
    public function findAllSimply(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.slug', 'p.titre', 'p.emplacement', 'p.rang', 'p.lien', 'f.filename AS cover')
            ->leftJoin('p.cover', 'f') // jointure avec la table Files
            ->where('p.statut = :val')
            ->setParameter('val', 1)
            ->andWhere('p.deletedAt IS NULL')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Pages
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
