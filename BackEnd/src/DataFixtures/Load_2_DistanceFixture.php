<?php

namespace App\DataFixtures;

use App\Entity\Distance;
use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Load_2_DistanceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jsonPath = dirname(__DIR__, 2) . '/config/data/distances.json';
        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data as $line) {
            foreach ($line['distances'] as $item){
                $stationParent = $manager->getRepository(Station::class)
                    ->findOneBy(['shortName' => $item['parent']]);

                $stationChild = $manager->getRepository(Station::class)
                    ->findOneBy(['shortName' => $item['child']]);

                if (!$stationParent || !$stationChild) {
                    echo "Station parent ou child not found" . $item['parent'] . " -> " . $item['child'] . "\n";
                    continue;
                }

                $distance = new Distance();
                $distance->setStationParent($stationParent);
                $distance->setStationChild($stationChild);
                $distance->setDistance((float)$item['distance']);

                $manager->persist($distance);

            }
        }

        $manager->flush();
    }
}
