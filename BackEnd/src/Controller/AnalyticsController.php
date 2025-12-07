<?php

namespace App\Controller;

use App\Repository\AnalyticDistanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Validator\AnalyticsRequestValidator;

class AnalyticsController extends AbstractController
{
    #[Route('/stats/distances', name: 'stats_distances', methods: ['GET'])]
    public function getAnalyticDistances(
        Request $request, AnalyticDistanceRepository $tripRepository, AnalyticsRequestValidator $validator): JsonResponse
    {
        $fromRaw = $request->query->get('from');
        $toRaw = $request->query->get('to');
        $groupByRaw = $request->query->get('groupBy');

        $validation = $validator->validate($fromRaw, $toRaw, $groupByRaw);

        if (!empty($validation['errors'])) {
            return new JsonResponse($validation['errors'][0], 400);
        }

        $fromDate = $validation['fromDate'];
        $toDate   = $validation['toDate'];
        $groupBy = $validation['groupBy'];

        $results = $tripRepository->getDistances($fromDate, $toDate, $groupBy);

        $responseItems = array_map(function($r) use ($groupBy, $fromDate, $toDate) {
            $periodStart = new \DateTimeImmutable($r['periodStart']);
            switch ($groupBy) {
                case 'day':
                    $periodEnd = $periodStart;
                    break;
                case 'month':
                    $periodEnd = $periodStart->modify('last day of this month');
                    break;
                case 'year':
                    $periodEnd = $periodStart->modify('last day of December');
                    break;
                case 'none':
                default:
                    $periodStart = $fromDate;
                    $periodEnd = $toDate;
                    break;
            }

            return [
                'analyticCode' => $r['analyticCode'],
                'totalDistanceKm' => round((float)$r['totalDistance'], 2),
                'periodStart' => $periodStart->format('Y-m-d'),
                'periodEnd' => $periodEnd->format('Y-m-d'),
                'group' => $groupBy,
            ];
        }, $results);


        return $this->json([
            'from' => $fromDate?->format('Y-m-d'),
            'to' => $toDate?->format('Y-m-d'),
            "groupBy" => $groupBy,
            'items' => $responseItems,
        ]);
    }
}
