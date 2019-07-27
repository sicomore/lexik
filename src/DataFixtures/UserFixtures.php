<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
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
        $connection = $manager->getConnection();
        $connection->exec("ALTER TABLE users AUTO_INCREMENT = 1;");

        $faker = Faker\Factory::create('fr_FR');

        $user = (new User())
            ->setNom('admin')
            ->setPrenom('Jean-Paul')
            ->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder
                ->encodePassword($user, 'azerazer'));

        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder
                ->encodePassword($user, 'azerazer'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
