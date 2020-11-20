<?php
/**
 * Created by PhpStorm.
 * User: camille
 * Date: 29/10/2020
 * Time: 11:25
 */

namespace App\DataFixtures;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UsersTeams;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TeamFixtures extends Fixture
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

            $userTeams = new UsersTeams();
            $team = new Team();
            $user = new User();

            $password = $faker->password;
            $user->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setSummonerLol($faker->name)
                ->setIsActive(true)
                ->setIsBanned(false);


            $team->setName($faker->word);

            $manager->persist($user);
            $manager->persist($team);

            $userTeams->setUser($user)
                ->setTeam($team)
                ->setInvitation(true);


            $manager->persist($userTeams);
        }
        $manager->flush();
    }

}