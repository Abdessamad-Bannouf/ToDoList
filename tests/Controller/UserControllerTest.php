<?php 
    namespace App\Tests\Controller;

    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGenerator;

    class UserControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;

        public function setUp(): Void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');

            $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);

            $this->testUser = $this->userRepository->findOneByEmail('abdessamad.bannouf@laposte.net');

            $this->client->loginUser($this->testUser);
        }

        public function testUserList()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
            
            $info = $crawler->filter('h1')->text();
            $info = $string = trim(preg_replace('/\s\s+/', ' ', $info));

            $this->assertSame("Liste des utilisateurs", $info);
            
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }

        public function testUserAdd()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

            $this->assertSame('Nom d\'utilisateur', $crawler->filter('label[for="user_username"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[username]"]')->count());

            $this->assertSame('Mot de passe', $crawler->filter('label[for="user_password_first"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[password][first]"]')->count());

            $this->assertSame('Adresse email', $crawler->filter('label[for="user_email"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[email]"]')->count());

            $form = $crawler->selectButton('Ajouter')->form();

            $form['user[username]'] = 'test130000';
            $form['user[password][first]'] = 'test0123';
            $form['user[password][second]'] = 'test0123';
            $form['user[email]'] = 'test@test.comm';

            
            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
            $crawler = $this->client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-success','L\'utilisateur a bien été ajouté.');
        }

        public function testUserEdit()
        {
            $crawler = $this->client->request(Request::METHOD_GET, "/users/" . rand(1, 6) . "/edit");

            $this->assertSame('Nom d\'utilisateur', $crawler->filter('label[for="user_username"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[username]"]')->count());

            $this->assertSame('Mot de passe', $crawler->filter('label[for="user_password_first"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[password][first]"]')->count());

            $this->assertSame('Adresse email', $crawler->filter('label[for="user_email"]')->text());
            $this->assertEquals(1, $crawler->filter('input[name="user[email]"]')->count());

            $form = $crawler->selectButton('Modifier')->form();

            $form['user[username]'] = 'test modifffffff';
            $form['user[password][first]'] = 'test0123';
            $form['user[password][second]'] = 'test0123';
            $form['user[email]'] = 'test@modifffffff.com';

            
            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
            $crawler = $this->client->followRedirect();
            $this->assertSelectorTextContains('div.alert.alert-success','Superbe ! L\'utilisateur a bien été modifié');
        }

        // FEATURE DELETE USER NON EXISTANTE
        /*public function testUserDelete()
        {
            $crawler = $this->client->request(Request::METHOD_GET, "/tasks/" . rand(1, 6) . "/delete");

            $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

            $crawler = $this->client->followRedirect();

            $this->assertSelectorTextContains('div.alert.alert-success','Superbe ! L\'utilisateur a bien été supprimée.');
        }*/
    }
?>