<?php

namespace Tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\AbstractTestController;

class DefaultControllerTest extends AbstractTestController
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe :")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur :")')->count());
    }

    public function testIndexWithAuth()
    {
        $this->logInUser();

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Consulter la liste des tâches à faire")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Créer une nouvelle tâche")')->count());

    }
    /** test */
}

