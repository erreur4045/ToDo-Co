<?php
/**
 * Create by maxime
 * Date 3/6/2020
 * Time 2:15 PM
 * Project :  projet8
 * IDE : PhpStorm
 * FileName : UserTypeTest.php as UserTypeTest
 */

namespace Tests\AppBundle\Form;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testFormFields()
    {
        $data = [
            'username' => 'maxime',
            'password' => [
                'type' => PasswordType::class,
                'required' => true,
                'first_option' => 'maxime',
                'second_option' => 'maxime',
            ],
            'email' => 'maxime@maxime.com'
        ];

        $toCompare = $this->getMockBuilder(User::class)->getMock();
        $form = $this->factory->create(UserType::class, $toCompare);

        $user = $this->getMock(User::class);

        $user->setEmail('maximmmmmmme@maxime.com');
        $user->setUsername('maxime');
        $user->setPassword('maxime');
        //dump($user);
        $form->submit($data);
        static::assertEquals($user, $toCompare);
    }
}