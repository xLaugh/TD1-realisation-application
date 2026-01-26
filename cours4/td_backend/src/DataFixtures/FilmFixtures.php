<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Realisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {

            $realisateur = new Realisateur();
            $realisateur
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName());

            $film = new Film();
            $film
                ->setTitre($faker->sentence(3))
                ->setRealisateurId($realisateur);

            $manager->persist($realisateur);
            $manager->persist($film);
        }

        $manager->flush();
    }
}
