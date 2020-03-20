<?php
/**
 * Create by maxime
 * Date 3/5/2020
 * Time 7:44 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskControllerTest.php as TaskControllerTest
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Tests\AppBundle\AbstractTestController;

class TaskControllerTest extends AbstractTestController
{
    public function setUp(): void
    {
        parent::setUp();
        $this->reloadDataFixtures();
    }

    public function testGetTasksWithAdminAuth()
    {
        $this->logInAdmin();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertSame(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Se déconnecter")')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetTasksWithUserAuth()
    {
        $this->logInUser();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertSame(0, $crawler->filter('html:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Se déconnecter")')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetTasksWithoutAuth()
    {
        $this->client->request('GET', '/tasks');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAddTasksWithUserAuth()
    {
        $this->logInUser();

        $crawler = $this->client->request('POST', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'taskfdsgsdfgsdfg';
        $form['task[content]'] = 'contenu task';
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("Superbe ! La tâche a été bien été ajoutée.")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("taskfdsgsdfgsdfg")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("contenu task")')->count());
        $this->assertSame("http://localhost/tasks", $crawler->getUri());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetTasksAddPageWithUserAuth()
    {
        $this->logInUser();

        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertSame(1, $crawler->filter('html:contains("Retour à la liste des tâches")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Content")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Se déconnecter")')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleChangeStatutWithUserAuth()
    {
        $this->logInUser();

        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userCo = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $task = $em->getRepository(Task::class)->findOneBy(['user' => $userCo->getId()]);
        $this->client->request('GET', '/tasks/'.$task->getId().'/toggle');

        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("a bien été marquée comme")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("content2")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Title2")')->count());
        $this->assertSame("http://localhost/tasks", $crawler->getUri());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleChangeStatutWithUserAuthByNotOwnTask()
    {
        $this->logInAdmin();
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userCo = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $task = $em->getRepository(Task::class)->findOneBy(['user' => $userCo->getId()]);
        $this->client->request('GET', '/tasks/'.$task->getId().'/toggle');

        $crawler = $this->client->followRedirect();
        $this->assertSame("http://localhost/tasks", $crawler->getUri());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskWithUserAuthByNotOwnTask()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('POST', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'tasktodelete';
        $form['task[content]'] = 'contenu task';
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/logout');
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $taskToDelete = $em->getRepository(Task::class)->findOneBy(['title' => 'tasktodelete']);
        $this->logInUser();
        $this->client->request('DELETE', '/tasks/'.$taskToDelete->getId().'/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskWithAuthWhoCanDeleteTask()
    {
        $this->logInUser();
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userCo = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $taskToDelete = $em->getRepository(Task::class)->findOneBy(['title' => 'Title1', 'user' => $userCo->getId()]);
        $this->client->request('GET', '/tasks/'.$taskToDelete->getId().'/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("a bien été supprimée")')->count());
    }

    public function testEditTaskWithAuth()
    {
        $this->logInUser();
        /** @var EntityManager $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userCo = $em->getRepository(User::class)->findOneBy(['username' => 'username1']);
        $taskToEdite = $em->getRepository(Task::class)->findOneBy(['title' => 'Title1', 'user' => $userCo->getId()]);
        $crawler = $this->client->request('POST', '/tasks/'.$taskToEdite->getId().'/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'azertyui';
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("a bien été")')->count());
    }
}
