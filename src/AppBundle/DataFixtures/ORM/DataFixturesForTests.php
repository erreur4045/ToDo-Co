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

class DataFixturesForTests implements FixtureInterface
{
    public function load(ObjectManager $manager){

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail(str_shuffle('sdfsdfsdferwtwerxcbvbxcvb').'@Email.fr');
            $user->setUsername(str_shuffle('kikodfgsgsdfgudf'));
            $user->setPassword('$2y$13$5denqc6U/vV0R.5JD2/WUeSokO.1KTOS3aClChSdYs8ttkVxCANCG');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        for ($i = 1; $i <= 2; $i++) {
            $task = new Task();
            $task->setContent('content'.$i);
            $task->setCreatedAt(new \DateTime());
            $task->setTitle('Title'.$i);
            $task->setUser($user);
            $manager->persist($task);
        }

        $userAdmin = new User();
        $userAdmin->setEmail(str_shuffle('sdfsdfsdfxcvbxcvbxcvbxcv').'@Email.fr');
        $userAdmin->setUsername('userAdminTest');
        $userAdmin->setPassword('$2y$13$5denqc6U/vV0R.5JD2/WUeSokO.1KTOS3aClChSdYs8ttkVxCANCG');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userUser = new User();
        $userUser->setEmail(str_shuffle('sdfsdfsdfsadfasdfasdf').'@Email.fr');
        $userUser->setUsername('username1');
        $userUser->setPassword('$2y$13$5denqc6U/vV0R.5JD2/WUeSokO.1KTOS3aClChSdYs8ttkVxCANCG');
        $userUser->setRoles(['ROLE_ADMIN']);
        for ($i = 1; $i <= 2; $i++) {
            $task = new Task();
            $task->setContent('content'.$i);
            $task->setCreatedAt(new \DateTime());
            $task->setTitle('Title'.$i);
            $task->setUser($userUser);
            $manager->persist($task);
        }
        $manager->persist($userAdmin);
        $manager->persist($userUser);
        $manager->flush();
    }
}