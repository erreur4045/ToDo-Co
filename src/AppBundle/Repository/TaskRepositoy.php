<?php

/**
 * Create by maxime
 * Date 3/15/2020
 * Time 6:58 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : TaskRepositoy.php as TaskRepositoy
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TaskRepositoy extends ServiceEntityRepository
{
    /**
     * TaskRepositoy constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param Task $task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteTask(Task $task): void
    {
        $this->_em->remove($task);
        $this->_em->flush();
    }

    /**
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function editTaskTreatment()
    {
        $this->_em->flush();
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     * @throws ORMException
     */
    public function addTask(Task $task, User $user)
    {
        $task->setUser($user);
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * @param Task $task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function changeToggleStatus(Task $task): void
    {
        $task->toggle(!$task->isDone());
        $this->_em->flush();
    }

    /**
     * @param User $user
     * @return object[]
     */
    public function getTaskAdminRole(User $user)
    {
        return $this->_em
            ->getRepository('AppBundle:Task')
            ->findBy([
                    'user' => [
                        null,
                        'anonymous',
                        $user
                    ]
                ]);
    }

    /**
     * @param User $user
     * @return object[]
     */
    public function getTaskUserRole(User $user)
    {
        return $this->_em
            ->getRepository('AppBundle:Task')
            ->findBy(['user' => $user]);
    }
}
