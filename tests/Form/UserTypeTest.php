<?php 
    namespace App\Tests\Form;

    use App\Form\UserType;
    use App\Entity\User;
    use Symfony\Component\Form\Test\TypeTestCase;
    
    class UserTypeTest extends TypeTestCase
    {
        public function testUserSubmitValidData()
        {
            $formData = [
                'email' => 'abdessamad.bannouf@laposte.net',
                'password' => ["first"=>"0123", "second"=>"0123"],
                'username' => 'Masakyu',
            ];
    
            $model = new User();

            $form = $this->factory->create(UserType::class, $model);

            $expected = new User();
            $expected->setEmail($formData['email']);
            $expected->setUsername($formData['username']);
            $expected->setPassword($formData['password']['first']);
    
            $form->submit($formData);
    
            $this->assertTrue($form->isSynchronized());

            $this->assertEquals($expected, $model);
        }
    
        public function testUserCustomFormView()
        {
            $formData = new User();
    
            $view = $this->factory->create(UserType::class, $formData)
                ->createView();
            $this->assertArrayHasKey('email', $view->children);
            $this->assertSame('', $view->children['email']->vars["value"]);

            $this->assertArrayHasKey('username', $view->children);
            $this->assertSame('', $view->children['username']->vars["value"]);

            $this->assertArrayHasKey('password', $view->children);
            $this->assertSame(null, $view->children['password']->vars["value"]['first']);
        }
    }
?>