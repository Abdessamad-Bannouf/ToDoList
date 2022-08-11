<?php 
    namespace App\Test\Controller;

    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGenerator;

    class SecurityControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;

        public function setUp(): Void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');

            $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        }

        public function testShowLoginForm()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_login'));

            $this->assertSame(1, $crawler->filter('form')->count());
        }

        public function testSuccessfullLogin()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_login'));

            $form = $crawler->selectButton('Sign in')->form();
            $form['email'] = 'abdessamad.bannouf@laposte.net';
            $form['password'] = 'test1234';

            $this->client->submit($form);

            $crawler = $this->client->followRedirect();

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            $this->assertSame('Se déconnecter', $crawler->filter('a.pull-right.btn.btn-danger')->text());

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }
?>