<?php

namespace App\Repository;

use App\Entity\ProjectVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectVote::class);
    }

    public function hasUserVoted(string $voterHash, int $projectId): bool
    {
        return (bool) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.voterHash = :hash')
            ->andWhere('v.project = :project')
            ->setParameter('hash', $voterHash)
            ->setParameter('project', $projectId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByProject(int $projectId): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.project = :project')
            ->setParameter('project', $projectId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deleteByMonth(string $month): void
    {
        $this->_em->createQuery(
            'DELETE FROM App\Entity\ProjectVote v
             WHERE v.project IN (
                SELECT p.id FROM App\Entity\ProjectImpact p WHERE p.month = :month
             )'
        )->setParameter('month', $month)->execute();
    }
}