<?php


namespace App\DataFixtures;


use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AuthorFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }

    const AUTHORS = [
        "Serge" => [
            "lastName" => "Brussolo",
            "nationality" => "Français",
            "book" => ["book_0", "book_1"]
        ],

        "Brigitte" => [
            "lastName" => "Aubert",
            "nationality" => "Française",
            "book" => ["book_2"]
        ],

        "Robin" => [
            "lastName" => "Hobb",
            "nationality" => "Américaine",
            "book" => ["book_3"]
        ]
    ];


    public function load(ObjectManager $manager)
    {

        // Création des auteurs

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 95; $i++) {
            $author = new Author();
            $author
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setNationality('FR')
                ->setDateOfBirth(new \DateTime('NOW'))
                ->setCreatedAt(new \DateTime('NOW'))
                ->addBook($this->getReference("book_" . $i));
            $manager->persist($author);
            $manager->persist($author);
        }

        foreach (self::AUTHORS as $firstName => $data) {
            $author = new Author();
            $author
                ->setFirstName($firstName)
                ->setLastName($data['lastName'])
                ->setNationality($data['nationality'])
                ->setDateOfBirth(new \DateTime('NOW'))
                ->setCreatedAt(new \DateTime('NOW'));
            foreach ($data['book'] as $book) {
                $author->addBook($this->getReference($book));
            }
            $manager->persist($author);
        }
        $manager->flush();
    }
}