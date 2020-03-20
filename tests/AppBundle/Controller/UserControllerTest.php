<?php
/**
 * Create by maxime
 * Date 3/16/2020
 * Time 2:43 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : UserControllerTest.php as UserControllerTest
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\UserController;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Form;
use Tests\AppBundle\AbstractTestController;

class UserControllerTest extends AbstractTestController
{
    public function setUp(): void
    {
        parent::setUp();
        self::reloadDataFixtures();
    }

    public function testListActionWithAuthAdmin()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Liste des utilisateurs")')->count());
    }

    public function testListActionWithAuthUser()
    {
        $this->logInUser();

        $this->client->request('GET', '/users');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

    }

    public function testListActionWithoutAuth()
    {
        $this->client->request('GET', '/users');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe :")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur :")')->count());
    }

    public function testListUserActionWithAuthAdmin()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/users/create');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("rôle administrateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
    }

    public function testAddUserPageWithAuthUser()
    {
        $this->logInUser();

        $this->client->request('GET', '/users/create');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

    }

    public function testEditActionWithoutAuth()
    {
        $this->client->request('GET', '/users/create');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe :")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur :")')->count());
    }

    public function testCreateActionWithAuthAdmin()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('POST', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'azertyui';
        $form['user[password][second]'] = 'azertyui';
        $form['user[email]'] = 'test@email.com';
        $form['user[roles]'] = ['ROLE_ADMIN'];
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("Superbe ! L\'utilisateur a bien été ajouté.")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Adresse d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Adresse d\'utilisateur")')->count());
        $this->assertSame("http://localhost/users", $crawler->getUri());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditActionWithAuthAdminWithData()
    {
        $this->logInAdmin();
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $url = '/users/'.$user->getId().'/edit';
        $crawler = $this->client->request('POST', $url);
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'azertyui';
        $form['user[password][second]'] = 'azertyui';
        $form['user[email]'] = 'test@email.com';
        $form['user[roles]'] = ['ROLE_ADMIN'];
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("Superbe ! L\'utilisateur a bien été modifié")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Adresse d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Adresse d\'utilisateur")')->count());
        $this->assertSame("http://localhost/users", $crawler->getUri());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetEditUserPageWithAdminAuth()
    {
        $this->logInAdmin();
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $crawler = $this->client->request('GET', '/users/'.$user->getId().'/edit');
       // $this->assertSame(1, $crawler->filter('html:contains("rôle administrateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("rôle utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Adresse email")')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
