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
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
     */
    private $task;

    /**
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param mixed $task
     */
    public function setTask($task): void
    {
        $this->task = $task;
    }

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }


    /**
     * @param $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getUsername():string
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
     * @return string
     */
    public function getPassword():string
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
     * @return string
     */
    public function getEmail():string
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
     * @return array
     */
    public function getRoles():array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
    }
}
