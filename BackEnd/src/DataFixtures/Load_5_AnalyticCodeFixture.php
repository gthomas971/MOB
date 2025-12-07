<?php

namespace App\DataFixtures;

use App\Entity\AnalyticCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Load_5_AnalyticCodeFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $codes = [
            ['code' => 'fret', 'label' => 'Transport de fret'],
            ['code' => 'passager', 'label' => 'Transport de passagers'],
            ['code' => 'maintenance', 'label' => 'Maintenance et support'],
            ['code' => 'autre', 'label' => 'Autre'],
        ];

        foreach ($codes as $data) {
            $analyticCode = new AnalyticCode();
            $analyticCode->setCode($data['code']);
            $analyticCode->setLabel($data['label']);

            $manager->persist($analyticCode);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['analytic'];
    }

}
