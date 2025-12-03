<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DistanceRepository;
use App\Service\RouteCalculatorService;

final class RoutesController extends AbstractController
{
    #[Route('/routes', name: 'app_routes', methods: ['GET'])]
    public function index(
        Request $request,
        DistanceRepository $distanceRepo,
        RouteCalculatorService $routeCalculator
    ): JsonResponse {
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        if (!$start || !$end) {
            return $this->json(['error' => 'Missing parameters'], 400);
        }

        $distances = $distanceRepo->findAll();
        $graph = $routeCalculator->buildGraph($distances);

        $result = $routeCalculator->shortestPath($graph, $start, $end);
        $path = $result['path'];

        $segments = [];
        for ($i = 0; $i < count($path) - 1; $i++) {
            $segments[] = [
                'from' => $path[$i],
                'to' => $path[$i + 1],
                'distance' => $graph[$path[$i]][$path[$i + 1]]
            ];
        }

        return $this->json([
            'path' => $path,
            'segments' => $segments,
            'totalDistance' => round($result['distance'], 2)
        ]);
    }

}
