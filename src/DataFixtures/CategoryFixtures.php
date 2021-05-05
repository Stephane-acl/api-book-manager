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
        return [BookFixtures::class, LibraryFixtures::class];
    }

    const CATEGORY = [
        "Science-fiction" => [
            'book' => ['book_0', 'book_1', 'book_40', 'book_41', 'book_42', 'book_43', 'book_44']
        ],

        "Romans policier" => [
            'book' => ['book_2', 'book_45', 'book_46', 'book_47', 'book_48', 'book_49', 'book_50', 'book_51', 'book_52']
        ],

        "Horreur" => [
            'book' => ['book_4', 'book_5', 'book_6', 'book_7', 'book_36', 'book_37', 'book_38', 'book_39']
        ],

        "Thriller" => [
            'book' => ['book_8', 'book_9', 'book_10', 'book_11', 'book_53', 'book_54', 'book_55', 'book_56']
        ],

        "Drame" => [
            'book' => ['book_12', 'book_13', 'book_14', 'book_15', 'book_57', 'book_58', 'book_59', 'book_60']
        ],

        "Action" => [
            'book' => ['book_16', 'book_17', 'book_18', 'book_19', 'book_61', 'book_62', 'book_63', 'book_64']
        ],

        "Romans" => [
            'book' => ['book_20', 'book_21', 'book_22', 'book_23', 'book_65', 'book_66', 'book_67', 'book_68']
        ],

        "Mangas" => [
            'book' => ['book_24', 'book_25', 'book_26', 'book_27', 'book_69', 'book_70', 'book_71', 'book_72']
        ],

        "Comique" => [
            'book' => ['book_28', 'book_29', 'book_30', 'book_31']
        ],

        "Histoire" => [
            'book' => ['book_32', 'book_33', 'book_34', 'book_35']
        ],

    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY as $categoryName => $data) {
            $category = new Category();

            $category
                ->setName($categoryName)
                ->setLibrary($this->getReference("library_" . 1));
            foreach ($data['book'] as $book) {
                $category->addBook($this->getReference($book));
            }
            $manager->persist($category);
        }
        $manager->flush();
    }
}