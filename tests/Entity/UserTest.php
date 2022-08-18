<?php 
    namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class UserTest extends KernelTestCase
    {
        public function getEntity(): User
        {
            $user = new User();

            $user
                ->setEmail('test@test.net')
                ->setRoles(["ROLE_USER"])
                ->setUsername("test69000")
                ;

            return $user;
        }

        public function assertHasErrors(User $user, int $number = 0)
        {
            self::bootKernel();
            $errors = self::getContainer()->get('validator')->validate($user);

            $messages = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
            }
            
            $this->assertCount($number, $errors, implode(', ', $messages));
        }

        public function testValidEntity()
        {
            $user = $this->getEntity();

            $this->assertHasErrors($user, 0);
        }

        public function testInvalidBlankEmailEntity()
        {
            $this->assertHasErrors($this->getEntity()->setEmail(""), 1);
        }

        public function testInvalidBlankUsernameEntity()
        {
            $this->assertHasErrors($this->getEntity()->setUsername(""), 1);
        }

        public function testSalt()
        {
            $user = new User();
            $result = $user->getSalt();

            $this->assertSame($result, null);
        }

        public function testAddTasks()
        {
            $task1 = new Task;
            $task2 = new Task;

            $user = $this->getEntity()->addTask($task1);

            $this->assertSame($task1->getUser(), $user);
            // On s'assure qu'il y a qu'une tâche
            $this->assertCount(1, $user->getTasks());
            
            // On s'assure qu'il y a deux tâches
            $this->assertCount(2, $user->addTask($task2)->getTasks());
        }

        public function testRemoveTasks()
        {
            $task1 = new Task;
            $task2 = new Task;

            $user = $this->getEntity()->addTask($task1)->addTask($task2);

            // On s'assure de supprimer la premiere tâche
            $this->assertCount(1, $user->removeTask($task1)->getTasks());

            $this->assertCount(0, $user->removeTask($task2)->getTasks());
            $this->assertSame(null, $task2->getUser());
        }
    }
?>