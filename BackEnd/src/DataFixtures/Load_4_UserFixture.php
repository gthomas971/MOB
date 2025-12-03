<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Load_4_UserFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {

        $adminRole = $manager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_ADMIN']);

        if (!$adminRole) {
            throw new Exception('Le rôle ROLE_ADMIN doit exister avant de créer l’utilisateur.');
        }

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword(password_hash('password123', PASSWORD_DEFAULT));
        $user->addRole($adminRole);

        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user_role'];
    }
}
