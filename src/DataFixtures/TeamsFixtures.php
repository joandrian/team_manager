<?php

namespace App\DataFixtures;

use App\Entity\Teams;
use App\Entity\Players;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TeamsFixtures extends Fixture
{
        
    public function load(ObjectManager $manager): void
    {
        $this->createTeams('Barea','Madagascar','10000.00', $manager);
        $this->createTeams('Manchester United','England','20000.00', $manager);
        $this->createTeams('Chelsea','England','95698.00', $manager);
        $this->createTeams('Fc Barcelone','Spain','98456.00', $manager);
        $this->createTeams('Real Madrid FC','Spain','98762.00', $manager);
        $this->createTeams('PSG','France','108710.00', $manager);
        $this->createTeams('Bayern Munich','Germany','983150.00', $manager);
        $this->createTeams('Fc Koln','Germany','8000.00', $manager);

        $manager->flush();
    }

    public function createTeams(string $name, string $country, string $money_balance, ObjectManager $manager)
    {
        $team = new Teams();
        $team->setName($name);
        $team->setCountry($country);
        $team->setMoneyBalance($money_balance);
        //Set Players to the team
        $faker = Faker\Factory::create('fr_FR');
        //Create 10 players for the team
        for($i = 0; $i < 10; $i++)
        {
            $player = new Players();
            $player->setName($faker->lastName);
            $player->setSurname($faker->firstName);
            $player->setTeams($team);
            $manager->persist($player);
            $team->addPlayer($player);
        }
        $manager->persist($team);
        return $team;
    }
}
