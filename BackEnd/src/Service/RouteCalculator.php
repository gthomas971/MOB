<?php

namespace App\Service;

class RouteCalculator
{
    public function buildGraph(array $distances): array
    {
        $graph = [];

        foreach ($distances as $distance) {
            $stationNameP = $distance->getStationParent()->getShortName();
            $stationNameC = $distance->getStationChild()->getShortName();
            $dist = $distance->getDistance();

            $value = ($dist == (int)$dist) ? (int)$dist : (float)$dist;

            $graph[$stationNameP][$stationNameC] = $value;
            $graph[$stationNameC][$stationNameP] = $value;
        }

        return $graph;
    }

    public function shortestPath(array $graph, string $start, string $end): array
    {
        $distances = [];
        $previous = [];
        $unvisited = [];

        foreach ($graph as $node => $edges) {
            $distances[$node] = INF;
            $previous[$node] = null;
            $unvisited[$node] = INF;
        }

        $distances[$start] = 0;
        $unvisited[$start] = 0;

        while (!empty($unvisited)) {
            asort($unvisited);
            $currentNode = key($unvisited);
            unset($unvisited[$currentNode]);

            if ($currentNode === $end) {
                break;
            }

            foreach ($graph[$currentNode] as $neighbor => $cost) {
                if (!isset($unvisited[$neighbor])) {
                    continue;
                }

                $alternativeDistance = $distances[$currentNode] + $cost;

                if ($alternativeDistance < $distances[$neighbor]) {
                    $distances[$neighbor] = $alternativeDistance;
                    $previous[$neighbor] = $currentNode;
                    $unvisited[$neighbor] = $alternativeDistance;
                }
            }
        }

        $path = [];
        $node = $end;

        while ($node !== null) {
            array_unshift($path, $node);
            $node = $previous[$node];
        }

        return [
            'path' => $path,
            'distance' => $distances[$end]
        ];
    }
}
