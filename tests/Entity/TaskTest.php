<?php 
    namespace App\Test\Entity;

    use App\Entity\Task;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class TaskTest extends KernelTestCase
    {
        public function getEntity(): Task
        {
            $task = new Task();

            $task
                ->setCreatedAt(new \DateTime())
                ->setTitle("Mon titre de test")
                ->setContent("Mon contenu de test")
                ->toggle(0);

            return $task;
        }

        public function assertHasErrors(Task $task, int $number = 0)
        {
            self::bootKernel();
            $errors = self::getContainer()->get('validator')->validate($task);

            $messages = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
            }
            
            $this->assertCount($number, $errors, implode(', ', $messages));
        }

        public function testValidEntity()
        {
            $task = $this->getEntity();

            $this->assertHasErrors($task, 0);
        }

        public function testInvalidBlankTitleEntity()
        {
            $this->assertHasErrors($this->getEntity()->setTitle(""), 1);
        }

        public function testInvalidBlankContentEntity()
        {
            $this->assertHasErrors($this->getEntity()->setContent(""), 1);
        }
    }
?>