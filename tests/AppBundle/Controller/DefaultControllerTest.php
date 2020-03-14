<?php

namespace Tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\AbstractTestController;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
