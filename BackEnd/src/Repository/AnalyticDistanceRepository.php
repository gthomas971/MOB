<?php

namespace App\Repository;

use App\Entity\AnalyticDistance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
class AnalyticDistanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyticDistance::class);
    }

    /**
     * Retourne la somme des distances par code analytique.
     *
     * @param \DateTimeInterface|null $from Date de début (optionnel)
     * @param \DateTimeInterface|null $to   Date de fin (optionnel)
     *
     * @return array Exemple : [['analyticCode' => 'fret', 'totalDistance' => 1200.5], ...]
     */
 public function getDistances(\DateTimeInterface $from = null, \DateTimeInterface $to = null, string $groupBy = 'none'): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('ac.code AS analyticCode, SUM(t.distanceKm) AS totalDistance')
            ->join('t.analyticCode', 'ac');

        switch ($groupBy) {
            case 'day':
                $qb->addSelect("DATE(t.date) AS periodStart")
                    ->groupBy('ac.code, periodStart');
                break;
            case 'month':
                $qb->addSelect("DATE_TRUNC('month', t.date) AS periodStart")
                    ->groupBy('ac.code, periodStart');
                break;
            case 'year':
                $qb->addSelect("DATE_TRUNC('year', t.date) AS periodStart")
                    ->groupBy('ac.code, periodStart');
                break;
            case 'none':
            default:
                $qb->groupBy('ac.code');
                break;
        }


        if ($from) {
            $qb->andWhere('t.date >= :from')->setParameter('from', $from);
        }

        if ($to) {
            $qb->andWhere('t.date <= :to')->setParameter('to', $to);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getAvailablePeriod(): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('MIN(a.date) as minDate, MAX(a.date) as maxDate');

        $result = $qb->getQuery()->getSingleResult();

        return [
            'from' => new \DateTimeImmutable($result['minDate']),
            'to'   => new \DateTimeImmutable($result['maxDate']),
        ];
    }


}
