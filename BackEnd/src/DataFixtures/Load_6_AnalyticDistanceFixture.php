<?php

namespace App\DataFixtures;

use App\Entity\AnalyticDistance;
use App\Entity\AnalyticCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Load_6_AnalyticDistanceFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $analyticCodes = $manager->getRepository(AnalyticCode::class)->findAll();

        if (!$analyticCodes) {
            throw new \Exception('Vous devez charger les AnalyticCodeFixtures avant TripFixtures.');
        }

        for ($i = 0; $i < 50; $i++) {
            $trip = new AnalyticDistance();
            $trip->setDate($faker->dateTimeBetween('-1 year', 'now'));
            $trip->setDistanceKm($faker->randomFloat(1, 5, 500));

            $trip->setAnalyticCode($faker->randomElement($analyticCodes));

            $manager->persist($trip);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['analytic'];
    }
}
