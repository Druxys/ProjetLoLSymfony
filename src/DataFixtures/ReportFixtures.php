<?php
/**
 * Created by PhpStorm.
 * User: camille
 * Date: 17/11/2020
 * Time: 14:05
 */

namespace App\DataFixtures;


use App\Entity\Report;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ReportFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();


        for ($i = 0; $i < 10; $i++){
            $report = new report();
            $user = new User();

            $password = $faker->password;
            $user->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setSummonerLol($faker->name)
                ->setIsActive(true)
                ->setIsBanned(false);

            $report->setUser($user)
                ->setIdUserReported($faker->numberBetween(1,20))
                ->setReason($faker->word);
            $manager->persist($user);
            $manager->persist($report);
        }
        $manager->flush();
    }

}