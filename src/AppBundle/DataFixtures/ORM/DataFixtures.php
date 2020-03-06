<?php
/**
 * Create by maxime
 * Date 3/5/2020
 * Time 8:56 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : DataFixtures.php as DataFixtures
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DataFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager){

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('Email'.$i);
            $user->setUsername('username'.$i);
            $user->setPassword('$2y$13$5denqc6U/vV0R.5JD2/WUeSokO.1KTOS3aClChSdYs8ttkVxCANCG');
            $manager->persist($user);
        }

        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setContent('content 1');
            $task->setCreatedAt(new \DateTime());
            $task->setTitle('Title');
            $manager->persist($task);
        }
        $manager->flush();
    }
}