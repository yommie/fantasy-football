<?php

namespace App\DataFixtures;

use App\Factory\PlayerFactory;
use App\Factory\TeamFactory;
use App\Factory\UserFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::createMany(25);

        $users[] = UserFactory::createOne([
            'email'     => "tommy@eagan.com",
            'roles'     => [],
            'lastName'  => "Eagan",
            'password'  => "secret",
            'firstName' => "Tommy"
        ]);

        foreach ($users as $user) {
            TeamFactory::createOne(["owner" => $user]);
        }

        $manager->flush();
    }
}
