<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TournamentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i=0; $i < 10 ; $i++) { 


            $tournament = new Tournament();
            $tournament->setName($faker->name)
            ->setStartTournament(new DateTime('now'))
            ->setEndTournament(new DateTime('now'))
            ->setNumbersParticipants($faker->randomNumber)
            ->setTypeTournament("5vs5")
            ->setGroupStage(false)
            ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
            ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
          
            $manager->persist($tournament);

        }
        $manager->flush();
    }
}
