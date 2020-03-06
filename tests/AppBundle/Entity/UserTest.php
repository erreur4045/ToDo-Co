<?php
/**
 * Create by maxime
 * Date 3/6/2020
 * Time 1:24 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskTest.php as TaskTest
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class UserTest extends TestCase
{
    private $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    public function testGetUsername()
    {
        $this->user->setUsername('maxime');
        static::assertSame('maxime', $this->user->getUsername());
    }

    public function testGetPassword()
    {
        $this->user->setPassword('maxime');
        static::assertSame('maxime', $this->user->getPassword());
    }

    public function testGetEmail()
    {
        $this->user->setEmail('maxime');
        static::assertSame('maxime', $this->user->getEmail());
    }

}