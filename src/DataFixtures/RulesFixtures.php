<?php

namespace App\DataFixtures;
use App\Entity\Rules;
use App\Entity\Tournament;
use Faker\Factory;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RulesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $rules = new Rules();
            $tournament = new Tournament();


            $tournament->setName($faker->name)
            ->setStartTournament(new DateTime('now'))
            ->setEndTournament(new DateTime('now'))
            ->setNumbersParticipants($faker->randomNumber)
            ->setTypeTournament("5vs5")
            ->setGroupStage(false)
            ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
            ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));

        
            $rules->setDescription($faker->text($maxNbChars = 250) )
            ->setTournament($tournament)
            ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
            ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($rules);
            $manager->persist($tournament);
        }
        $manager->flush();
    }
}
