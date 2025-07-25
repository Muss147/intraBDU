<?php

namespace App\Repository;

use App\Entity\Postuler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postuler>
 */
class PostulerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postuler::class);
    }

    public function findExistingWirer(string $name, string $email, int $offre): ?Postuler
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.offre', 'o')
            ->andWhere('p.nomAgent LIKE :name AND p.email LIKE :email AND o.id LIKE :offre')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('email', '%' . $email . '%')
            ->setParameter('offre', '%' . $offre . '%')
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    //    /**
    //     * @return Postuler[] Returns an array of Postuler objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Postuler
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
