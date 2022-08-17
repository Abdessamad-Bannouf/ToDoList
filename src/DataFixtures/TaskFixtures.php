<?php

namespace App\DataFixtures;

use App\DataFixtures\UserFixtures;
use App\Entity\Task;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
* @codeCoverageIgnore
*/
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        dump($this->getReference(UserFixtures::USER_REFERENCE . '_' . mt_rand(0,9)));
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        // Créer 10 tâches
        for ($j=0; $j<10; $j++)
        {
            $task = new Task;

            $date = new \DateTime();
            $content = join($faker->paragraphs(1));

            $task->setCreatedAt($date)
                ->setTitle($faker->title)
                ->setContent($content)
                ->toggle($faker->boolean);
            $task->setUser($this->getReference(UserFixtures::USER_REFERENCE . '_' . mt_rand(0,9)));

            $manager->persist($task);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}