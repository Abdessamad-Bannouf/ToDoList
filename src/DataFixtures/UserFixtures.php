<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
* @codeCoverageIgnore
*/
class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        // Créer 10 utilisateurs
        for ($j=0; $j<10; $j++)
        {
            $user = new User;

            $user->setEmail($faker->email)
                ->setRoles(["ROLE_USER"])
                ->setPassword($this->encoder->hashPassword($user, 'password'))
                ->setUsername($faker->userName);

            if($j === 9)
            {
                $user = new User;

                $user->setEmail('abdessamad.bannouf@laposte.net')
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($this->encoder->hashPassword($user, 'test1234'))
                ->setUsername('Masakyu');
            }

            $manager->persist($user);
        }
        
        

        $manager->flush();
    }
}