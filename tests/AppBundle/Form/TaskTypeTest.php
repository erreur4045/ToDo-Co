<?php
/**
 * Create by maxime
 * Date 3/6/2020
 * Time 1:58 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskTypeTest.php as TaskTypeTest
 */

namespace Tests\AppBundle\Form;


use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{

    public function testFormFields()
    {
        $user = new User();
        $formData = [
            'title' => 'titre',
            'content' => 'contenu de l\'article',
            'user' => $user,
        ];

        $taskToCompare = $this->createMock(Task::class);

        $form = $this->factory->create(TaskType::class, $taskToCompare);

        $task = $this->createMock(Task::class);
        $task->setTitle('titre');
        $task->setContent('contenu de l\'article');
        $task->setUser($user);

        $form->submit($formData);

        $this->assertTrue($form->isValid());
        $this->assertEquals($task, $taskToCompare);
    }
}