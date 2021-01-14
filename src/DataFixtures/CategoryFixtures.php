<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }

    const CATEGORY = [
        "Science-fiction" => [
            'book' => ['book_0', 'book_1', 'book_3']
        ],

        "Romans policier" => [
            'book' => ['book_2']
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY as $categoryName => $data) {
            $category = new Category();

            $category->setName($categoryName);
            foreach ($data['book'] as $book) {
                $category->addBook($this->getReference($book));
            }
            $manager->persist($category);
        }
        $manager->flush();
    }
}