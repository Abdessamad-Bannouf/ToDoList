<?php

namespace App\DataFixtures;

use App\Entity\Task;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
* @codeCoverageIgnore
*/
class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

            $manager->persist($task);
        }
        
        $manager->flush();
    }
}