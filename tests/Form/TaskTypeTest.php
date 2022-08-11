<?php 
    namespace App\Tests\Form\Type;

    use App\Form\TaskType;
    use App\Entity\Task;
    use Symfony\Component\Form\Test\TypeTestCase;
    
    class TaskTypeTest extends TypeTestCase
    {
        public function testTaskSubmitValidData()
        {
            $formData = array(
                'title' => 'test title form',
                'content' => 'test content form',
            );
    
            $model = new Task();

            $form = $this->factory->create(TaskType::class, $model);
    
            $expected = new Task();
            $expected->setTitle($formData['title']);
            $expected->setContent($formData['content']);

            $form->submit($formData);
    
            $this->assertTrue($form->isSynchronized());
            
            $this->assertEquals($expected, $model);
        }
    
        public function testTaskCustomFormView()
        {
            $formData = new Task();
            // ... prepare the data as you need
    
            // The initial data may be used to compute custom view variables
            $view = $this->factory->create(TaskType::class, $formData)
                ->createView();
            $this->assertArrayHasKey('title', $view->children);
            $this->assertSame('', $view->children['title']->vars["value"]);

            $this->assertArrayHasKey('content', $view->children);
            $this->assertSame('', $view->children['content']->vars["value"]);
        }
    }
?>