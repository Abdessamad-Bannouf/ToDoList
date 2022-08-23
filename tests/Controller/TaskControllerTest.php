<?php 
    namespace App\Tests\Controller;

    use App\Entity\Task;
    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGenerator;

    class TaskControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;

        public function setUp(): Void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');

            $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);

            $this->testUser = $this->userRepository->findOneByEmail('abdessamad.bannouf@laposte.net');
        }

        public function loginWithAdmin(): void
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_login'));

            $form = $crawler->selectButton('Sign in')->form();
            $form['email'] = 'abdessamad.bannouf@laposte.net';
            $form['password'] = 'test1234';

            $this->client->submit($form);
        }

        public function testTaskList()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
            
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }

        public function testTaskAdd()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));

            $this->assertSame('Title', $crawler->filter('label[for="task_title"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="task[title]"]')->count());

            $this->assertSame('Content', $crawler->filter('label[for="task_content"]')->text());
            $this->assertEquals(1, $crawler->filter('textarea[name="task[content]"]')->count());

            $form = $crawler->selectButton('Ajouter')->form();

            $form['task[title]'] = 'Test Super titre de tache';
            $form['task[content]'] = 'Test Contenu de la supertache blablabla.';
            
            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
            $crawler = $this->client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-success','Superbe ! La tâche a été bien été ajoutée.');
        }

        public function testTaskEdit()
        {
            $this->loginWithAdmin();

            $crawler = $this->client->request(Request::METHOD_GET, "/tasks/" . rand(1, 6) . "/edit");

            $this->assertSame('Title', $crawler->filter('label[for="task_title"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="task[title]"]')->count());
            
            $this->assertSame('Content', $crawler->filter('label[for="task_content"]')->text());
            $this->assertEquals(1, $crawler->filter('textarea[name="task[content]"]')->count());

            $form = $crawler->selectButton('Modifier')->form();
            $form['task[title]'] = 'Test du super titre de tache modifié';
            $form['task[content]'] = 'Test du coontenu de la supertache modifié.';
            
            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
            $crawler = $this->client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-success','Superbe ! La tâche a bien été modifiée.');
        }

        public function testToggleTask()
        {
            $crawler = $this->client->request(Request::METHOD_GET, "/tasks/" . rand(1, 6) . "/toggle");

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            $crawler = $this->client->followRedirect();

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
        }

        public function testTaskDelete()
        {
            $this->loginWithAdmin();

            $crawler = $this->client->request(Request::METHOD_GET, "/tasks/" . rand(1, 6) . "/delete");

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            $crawler = $this->client->followRedirect();

            $this->assertSelectorTextContains('div.alert.alert-success','Superbe ! La tâche a bien été supprimée.');
        }
    }
?>