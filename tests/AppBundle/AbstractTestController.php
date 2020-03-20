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
use AppBundle\DataFixtures\ORM\DataFixturesForTests;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AbstractTestController extends WebTestCase
{

    protected $client;
    public function setUp():void
    {
        $this->client = static::createClient();
    }
    protected function logInAdmin()
    {
        $session = $this->client->getContainer()->get('session');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['username'=>'userAdminTest']);

        $token = new UsernamePasswordToken($user, null, 'main', ['ROLE_ADMIN']);
        $session->set('_security_'.'main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function logInUser()
    {
        $session = $this->client->getContainer()->get('session');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['username'=>'username1']);
        $token = new UsernamePasswordToken($user, null, 'main', ['ROLE_USER']);
        $session->set('_security_'.'main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
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
            new DataFixturesForTests(),
        ];
    }
}