<?php
/**
 * Create by maxime
 * Date 3/6/2020
 * Time 1:58 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskTypeTest.php as TaskTypeTest
 */

namespace Tests\Form;


use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testFormFields()
    {
        $data = [
            'title' => 'Titre test',
            'content' => 'Content test'
        ];

        $toCompare = $this->getMock(Task::class);
        $form = $this->factory->create(TaskType::class, $toCompare);

        $task = $this->getMock(Task::class);
        $task->setTitle('Titre test');
        $task->setContent('Content test');

        $form->submit($data);

        static::assertEquals($task, $toCompare);
    }
}