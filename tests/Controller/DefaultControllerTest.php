<?php 
    namespace App\Test\Controller;

    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGenerator;

    class DefaultControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;

        public function setUp(): Void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }

        public function testHomepageIsUp()
        {
            $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_default_index.name'));

            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }

        public function testIndex()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_default_index.name'));
            
            $this->assertSame(1,$crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
        }
    }
?>