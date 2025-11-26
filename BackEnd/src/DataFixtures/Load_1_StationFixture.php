<?php

namespace App\DataFixtures;

use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Load_1_StationFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jsonPath = dirname(__DIR__, 2) . '/config/data/stations.json';
        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data as $item) {
            $station = new Station();

            $station->setShortName($item['shortName']);
            $station->setLongName($item['longName']);

            $manager->persist($station);
        }

        $manager->flush();
    }
}
