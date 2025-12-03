<?php

namespace App\Repository;

use App\Entity\PageView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

class PageViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageView::class);
    }

    public function countAllPages(): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.routeName)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function countByMonth(int $year, int $month): int
    {
        $start = new \DateTimeImmutable("$year-$month-01 00:00:00");
        $end = $start->modify('last day of this month 23:59:59');

        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.visitedAt BETWEEN :start AND :end')
            ->setParameters(new ArrayCollection([
                new Parameter('start', $start),
                new Parameter('end', $end),
            ]))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSessionsByMonth(int $year, int $month): int
    {
        $start = new \DateTimeImmutable("$year-$month-01 00:00:00");
        $end = $start->modify('last day of this month 23:59:59');

        return $this->createQueryBuilder('v')
            ->select('COUNT(DISTINCT v.sessionId)')
            ->andWhere('v.visitedAt BETWEEN :start AND :end')
            ->setParameters(new ArrayCollection([
                new Parameter('start', $start),
                new Parameter('end', $end),
            ]))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getBounceRate(int $year, int $month): float
    {
        $start = new \DateTimeImmutable("$year-$month-01 00:00:00");
        $end = $start->modify('last day of this month 23:59:59');

        $sessions = $this->createQueryBuilder('v')
            ->select('v.sessionId, COUNT(v.id) as pageCount')
            ->andWhere('v.visitedAt BETWEEN :start AND :end')
            ->groupBy('v.sessionId')
            ->setParameters(new ArrayCollection([
                new Parameter('start', $start),
                new Parameter('end', $end),
            ]))
            ->getQuery()
            ->getResult();

        $total = count($sessions);
        $bounces = count(array_filter($sessions, fn($s) => $s['pageCount'] == 1));

        return $total > 0 ? ($bounces / $total) * 100 : 0;
    }

    public function getAverageSessionDuration(int $year, int $month): float
    {
        $start = new \DateTimeImmutable("$year-$month-01 00:00:00");
        $end = $start->modify('last day of this month 23:59:59');

        $sessions = $this->createQueryBuilder('v')
            ->select('v.sessionId, MIN(v.visitedAt) as startAt, MAX(v.visitedAt) as endAt')
            ->andWhere('v.visitedAt BETWEEN :start AND :end')
            ->groupBy('v.sessionId')
            ->setParameters(new ArrayCollection([
                new Parameter('start', $start),
                new Parameter('end', $end),
            ]))
            ->getQuery()
            ->getResult();

        $durations = array_map(fn($s) =>
            (new \DateTime($s['endAt']))->getTimestamp() - (new \DateTime($s['startAt']))->getTimestamp(),
            $sessions
        );

        $avg = count($durations) ? array_sum($durations) / count($durations) : 0;

        return round($avg / 60, 2); // en minutes
    }
}