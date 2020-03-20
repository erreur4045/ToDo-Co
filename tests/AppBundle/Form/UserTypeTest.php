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
        $formData = [
            'username' => 'mm',
            'password' => [
                'first_option' => 'passtest',
                'second_option' => 'passtest',
            ],
            'email' => 'mm@pp.ff',
            'roles' => ['ROLE_USER']
        ];

        $userToCompare = $this->createMock(User::class);

        $form = $this->factory->create(UserType::class, $userToCompare);

        $user = $this->createMock(User::class);
        $user->setUsername('mm');
        $user->setPassword('passtest');
        $user->setEmail('mm@pp.ff');
        $user->setRoles(['ROLE_USER']);

        $form->submit($formData);

        $this->assertEquals($user, $userToCompare);
        $this->assertInstanceOf(User::class, $form->getData());
    }
}