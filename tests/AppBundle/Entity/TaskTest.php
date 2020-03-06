<?php
/**
 * Create by maxime
 * Date 3/6/2020
 * Time 1:40 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskTypeTest.php as TaskTypeTest
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class TaskTest extends TestCase
{
    private $task;

    protected function setUp()
    {
        parent::setUp();
        $this->task = new Task();
    }

    public function testGetTitle()
    {
        $this->task->setTitle('maxime');
        static::assertSame('maxime', $this->task->getTitle());
    }

    public function testGetContent()
    {
        $this->task->setContent('maxime');
        static::assertSame('maxime', $this->task->getContent());
    }

    public function testGetId()
    {
        $this->task->setContent(42);
        static::assertSame(42, $this->task->getContent());
    }

    public function testGetCreatedAt()
    {
        $this->task->setCreatedAt(new \DateTime('2011-01-01T15:03:01'));
        //dump($this->task->getCreatedAt());
        static::assertEquals(new \DateTime('2011-01-01T15:03:01'), $this->task->getCreatedAt());
    }

    public function testIsDone()
    {
        $this->task->toggle(false);

        static::assertSame(false, $this->task->isDone());
    }
}