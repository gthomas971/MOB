<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Load_3_RoleFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $roles = ['ROLE_ADMIN', 'ROLE_TEST', 'ROLE_USER'];

        foreach ($roles as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user_role'];
    }
}
