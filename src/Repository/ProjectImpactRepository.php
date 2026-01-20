<?php

namespace App\Repository;

use App\Entity\ProjectImpact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectImpactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectImpact::class);
    }

    public function findPublishedForCurrentMonth(string $month): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.month = :month')
            ->andWhere('p.isPublished = true')
            ->andWhere('p.isArchived = false')
            ->setParameter('month', $month)
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findToArchive(string $month): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.month = :month')
            ->andWhere('p.isArchived = false')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult();
    }
}