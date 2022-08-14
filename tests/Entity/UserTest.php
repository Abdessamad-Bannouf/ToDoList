<?php 
    namespace App\Tests\Entity;

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
    }
?>