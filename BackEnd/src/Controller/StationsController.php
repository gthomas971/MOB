<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class StationsController extends AbstractController
{
    #[Route('/stations', name: 'app_station', methods: ['GET'])]
    public function index( StationRepository $stationRepo, SerializerInterface $serializer): JsonResponse
    {
        $stations = $stationRepo->findAll();

        $stationsArray = array_map(function($station) {
            return [
                'id' => $station->getId(),
                'short_name' => $station->getShortName(),
                'long_name' => $station->getLongName(),
            ];
        }, $stations);

        return $this->json([
            'success' => true,
            'data' => $stationsArray,
        ]);
    }
}
