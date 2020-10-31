<?php

namespace App\DataFixtures;

use App\Entity\Game;
use DateTime;
use Faker\Factory;
use App\Entity\Tournament;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {

            $game = new Game();
            $tournament = new Tournament();

            $tournament->setName($faker->name)
                ->setStartTournament(new DateTime('now'))
                ->setEndTournament(new DateTime('now'))
                ->setNumbersParticipants($faker->randomNumber)
                ->setTypeTournament("5vs5")
                ->setGroupStage(false)
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
            $game->setTeam1($faker->randomNumber)
                ->setTeam2($faker->randomNumber)
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setTournament($tournament);
            $manager->persist($game);
            $manager->persist($tournament);
        }
        $manager->flush();
    }
}
