<?php

namespace App\Tests\Service;

use App\Service\RouteCalculator;
use PHPUnit\Framework\TestCase;
use App\Entity\Distance;
use App\Entity\Station;

class RouteCalculatorServiceTest extends TestCase
{

    public function testBuildGraph(): void
    {
        $a = new Station();
        $a->setShortName('A');
        $b = new Station();
        $b->setShortName('B');
        $c = new Station();
        $c->setShortName('C');

        $d1 = new Distance();
        $d1->setStationParent($a);
        $d1->setStationChild($b);
        $d1->setDistance(5);

        $d2 = new Distance();
        $d2->setStationParent($a);
        $d2->setStationChild($c);
        $d2->setDistance(1);

        $d3 = new Distance();
        $d3->setStationParent($c);
        $d3->setStationChild($b);
        $d3->setDistance(1);

        $distances = [$d1, $d2, $d3];

        $service = new RouteCalculator();
        $graph = $service->buildGraph($distances);


        $expected = [
            'A' => ['B' => 5, 'C' => 1],
            'B' => ['A' => 5, 'C' => 1],
            'C' => ['A' => 1, 'B' => 1],
        ];

        $this->assertSame($expected, $graph);
    }

    public function testShortestPathIsCorrect(): void
    {
        $graph = [
            'A' => ['B' => 5, 'C' => 1],
            'B' => [],
            'C' => ['B' => 1]
        ];

        $service = new RouteCalculator();

        $result = $service->shortestPath($graph, 'A', 'B');

        $this->assertSame(['A', 'C', 'B'], $result['path']);
        $this->assertSame(2, $result['distance']);
    }
}
