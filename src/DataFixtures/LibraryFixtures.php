<?php


namespace App\DataFixtures;

use App\Entity\Library;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LibraryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $library = new Library();
        $libraryName = $faker->company;
        $library
            ->setLabel($libraryName)
            ->setAddress($faker->address)
            ->setCity($faker->city)
            ->setCpo($faker->postcode)
            ->setLogo($faker->imageUrl(150, 150));

        $this->addReference('library_' . 1, $library);
        $manager->persist($library);
        $manager->flush();
        printf("%12s %s\n", "Company:", $libraryName);
    }
}