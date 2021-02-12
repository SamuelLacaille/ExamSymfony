<?php

namespace App\DataFixtures;

use App\Entity\Acteur;
use App\Entity\Categorie;
use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

      $acteur = new Acteur();
      $acteur->setNom("Johnson");
      $acteur->setPrenom("Dwayne");
      $acteur->setAge(48);
      $manager->persist($acteur);

      $film = new Film();
      $film->setTitre("Expendables");
      $film->setAnnee(2010);
      $manager->persist($film);

      $categorie = new Categorie();
      $categorie->setNom("action");
      $manager->persist($categorie);


      $manager->flush();
    }
}
