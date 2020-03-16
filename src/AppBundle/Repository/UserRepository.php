<?php

/**
 * Create by maxime
 * Date 3/16/2020
 * Time 11:36 AM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : UserRepository.php as UserRepository
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository extends ServiceEntityRepository
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        parent::__construct($registry, User::class);
    }

    public function getUsers()
    {
        return $this->_em
            ->getRepository('AppBundle:User')
            ->findAll();
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     */
    public function addUser(User $user): void
    {
        $password = $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function editUser(User $user)
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->_em->flush();
    }
}
