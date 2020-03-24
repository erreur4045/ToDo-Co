<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="user")
     */

    private $task;

    /**
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param mixed $task
     * @codeCoverageIgnore
     */
    public function setTask($task): void
    {
        $this->task = $task;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param $id
     * @codeCoverageIgnore
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @param $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }


    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }


    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
