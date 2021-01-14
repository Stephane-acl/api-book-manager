<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Création d’un utilisateur de type “administrateur”

        $admin = new User();

        $firstName = 'Stéphane';
        $lastName = 'Acloque';
        $email = "stephane@gmail.com";
        $password = 'pass';

        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setEmail($email);
        $admin->setCreatedAt(new \DateTime('NOW'));;
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            $password
        ));

        $manager->persist($admin);

        printf("User created : \n");
        printf("%12s %s\n", "First name:", $firstName);
        printf("%12s %s\n", "Last name:", $lastName);
        printf("\nLogin informations : \n");
        printf("%12s %s\n", "Email:", $email);
        printf("%12s %s\n", "Password:", $password . "\n");

        $manager->flush();
    }
}

