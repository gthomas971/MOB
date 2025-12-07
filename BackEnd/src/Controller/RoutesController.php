<?php

namespace App\Controller;

use App\Repository\AnalyticCodeRepository;
use App\Validator\RoutesRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DistanceRepository;
use App\Service\RouteCalculatorService;
use App\Message\AnalyticDistanceMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class RoutesController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/routes', name: 'app_routes', methods: ['POST'])]
    public function index(
        Request $request,
        DistanceRepository $distanceRepo,
        RouteCalculatorService $routeCalculator,
        MessageBusInterface $bus,
        AnalyticCodeRepository $codeRepo,
        RoutesRequestValidator $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $fromStationId = $data['fromStationId'] ?? null;
        $toStationId = $data['toStationId'] ?? null;
        $analyticCodeStr = $data['analyticCode'] ?? null;

        $errors = $validator->validate($data);
        if (!empty($errors)) {
            return new JsonResponse($errors[0], 400);
        }

        $distances = $distanceRepo->findAll();
        $graph = $routeCalculator->buildGraph($distances);

        $result = $routeCalculator->shortestPath($graph, $fromStationId, $toStationId);
        $path = $result['path'];
        $totalDistance = round($result['distance'], 2);


        $segments = [];
        for ($i = 0; $i < count($path) - 1; $i++) {
            $segments[] = [
                'from' => $path[$i],
                'to' => $path[$i + 1],
                'distance' => $graph[$path[$i]][$path[$i + 1]]
            ];
        }

        $analyticCode = $codeRepo->findOneBy(['code' => $analyticCodeStr]);
        if ($analyticCode) {
            // TODO ASYNC MESSAGE
            $bus->dispatch(new AnalyticDistanceMessage(
                $totalDistance,
                $analyticCode->getId()
            ));
        }

        $id = Uuid::v4();

        return $this->json([
            'id' => (string)$id,
            'fromStationId' => $fromStationId,
            'toStationId' => $toStationId,
            'analyticCode' => $analyticCodeStr,
            'distanceKm' => round($totalDistance ,2),
            'path' => $path,
            'segments' => $segments,
            'createdAt' => (new \DateTimeImmutable())->format('c')
        ]);
    }

}
