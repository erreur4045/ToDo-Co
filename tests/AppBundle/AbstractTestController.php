<?php
/**
 * Create by maxime
 * Date 3/5/2020
 * Time 8:21 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : AbstractTestController.php as AbstractTestController
 */

namespace Tests\AppBundle;



use AppBundle\DataFixtures\ORM\DataFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractTestController extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = self::createClient([], [
            'HTTP_HOST' => '127.0.0.1:8000',
            'HTTPS' => true
        ]);

    }

    protected static function reloadDataFixtures()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        foreach (self::getFixtures() as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    private static function getFixtures()
    {
        return [
            new DataFixtures(),
        ];
    }
}