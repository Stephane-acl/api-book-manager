<?php


namespace App\DataFixtures;


use App\Entity\Book;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [LibraryFixtures::class];
    }

    const BOOKS = [
        'Cendres vives' => [

            'language' => 'Français',

            'description' => "Jérémie Stalion a 13 ans, il vit au Chili avec sa famille, à la lisière de la forêt amazonienne. 
                  Son père, ingénieur agronome, est employé par le gouvernement au défrichage de la jungle.
                  Son frère aîné, Jonah - sujet à des crises d'exaltation - exerce sur lui une tyrannie qui ne gêne en rien leurs parents, 
                  adeptes d'une éducation spartiate.
                  A la suite d'un étrange accident, Jonah disparaît. 
                  Il est présumé mort. La famille accuse Jérémie d'être à l'origine du drame, 
                  et le soupçonne d'avoir assassiné son frère par jalousie.
                  Ne supportant plus sa présence, les parents expédient Jérémie aux U.S.A. dans un inquiétant 
                  pensionnat militaire où l'on va s'employer à faire de lui un enfant-soldat rompu aux techniques 
                  d'infiltration et d'assassinat.",

            'nbrPages' => '320',

            'image' => 'https://images-na.ssl-images-amazon.com/images/I/912z1ySaNPL.jpg',

        ],

        "L'armure de vengeance" => [

            'language' => 'Français',

            'description' => "Par une nuit sans lune, Jehan de Montpéril, le chevalier errant, est chargé d'escorter au 
            fond de la forêt six fossoyeurs porteurs d'un cercueil bardé de fer. C'est une armure vide qu'il s'agit d'enterrer. 
            Une armure maléfique, une armure tueuse qui, dit-on, bouge toute seule et répète, passé minuit, les gestes de mort appris sur le champ de bataille.
                   Malgré cela, bien des chevaliers la convoitent, au risque de voir leur famille décimée par le vêtement de métal ensorcelé. 
                   Qu'importe ! N'a-t-il pas la réputation de rendre invincible celui qui s'en revêt ? Une malédiction pèse-t-elle vraiment sur l'armure ? 
                   Ou bien quelqu'un se sert-il de cette légende pour mener à bien une vengeance mystérieuse ?
                   Une enquête gothique et cruelle, par l'auteur du Chien de minuit, prix du Roman d'Aventures 1994, 
                   qui nous convie à une envoûtante exploration des sortilèges du Moyen Age.",

            'nbrPages' => '317',

            'image' => 'https://media.senscritique.com/media/000000150418/source_big/L_Armure_de_vengeance.jpg',

        ],

        'La morsure des ténébres' => [

            'language' => 'Français',

            'description' => "Après que le village de Jacksonville a été englouti dans les flammes de l'Enfer, 
            la petite poignée de survivants essaie de réapprendre à vivre normalement. 
            Mais les Forces des Ténèbres, toujours affamées, se rassemblent, et le Mal frappe de nouveau, 
            de la façon la plus atroce. Pour les habitants des cimetières, le meilleur moment de la vie, 
            c'est la mort, surtout la vôtre... Deux gamins et quelques adultes fatigués ne devraient pas peser bien lourd ! 
            C'est oublier que le courage et l'humour peuvent être aussi corrosifs que l'acide. Brigitte Aubert apporte ici, 
            avec sa maestria coutumière, une suite surprenante à Ténèbres sur Jacksonville.",

            'nbrPages' => '320',

            'image' => 'https://images-na.ssl-images-amazon.com/images/I/51jHII+yN8L.jpg',

        ],

        "L'apprenti assassin" => [

            'language' => 'Français',

            'description' => "Au royaume des six Duchés, le prince Chevalerie, de la famille régnante des Loinvoyant - par tradition, 
            le nom des seigneurs doit modeler leur caractère- décide de renoncer à son ambition de devenir roi-servant 
            en apprenant l'existence de Fitz, son fils illégitime. Le jeune bâtard grandit à Castelcerf, sous l'égide 
            du maître d'écurie Burrich. Mais le roi Subtil impose bientôt que FITZ reçoive, malgré sa condition, une 
            éducation princière.,L' enfant découvrira vite que le véritable dessein du monarque est autre : faire de 
            lui un assassin royal. Et tandis que les attaques des pirates rouges mettent en péril la contrée, Fitz va 
            constater à chaque instant que sa vie ne tient qu'à un fil : celui de sa lame...",

            'nbrPages' => '512',

            'image' => 'https://images.epagine.fr/603/9782290303603_1_75.jpg',

        ],
    ];

    public function load(ObjectManager $manager)
    {
        //Création de Livres

        $i = 0;

  $faker = Factory::create('fr_FR');

  for ($i = 0; $i < 95; $i++) {
      $book = new Book();
      $book
          ->setTitle($faker->title)
          ->setLanguage($faker->languageCode)
          ->setDescription($faker->text)
          ->setNbrPages($faker->randomDigit)
          ->setDateOfPublication(new DateTime('10-03-2020'))
          ->setCreatedAt(new DateTime('NOW'))
          ->setUpdatedAt(new DateTime('NOW'))
          ->setIsAvailable(rand(0, 1))
          ->setImage("https://www.lafinancepourtous.com/wp-content/thumbnails/uploads/2018/04/marche_livre_460-tt-width-460-height-260-fill-0-crop-0-bgcolor-eeeeee.png")
          ->setLibrary($this->getReference("library_" . 1));

      $this->addReference("book_" . $i, $book);
      $manager->persist($book);
  }

        foreach (self::BOOKS as $title => $data) {
            $book = new Book();
            $book
                ->setTitle($title)
                ->setLanguage($data["language"])
                ->setDescription($data['description'])
                ->setNbrPages($data["nbrPages"])
                ->setDateOfPublication(new DateTime('10-03-2020'))
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
                ->setIsAvailable(rand(0, 1))
                ->setImage($data["image"])
                ->setLibrary($this->getReference("library_" . 1));

            $this->addReference("book_" . $i, $book);
            $manager->persist($book);
            $i++;
        }


        $manager->flush();
    }
}