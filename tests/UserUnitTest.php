<?php

namespace App\Tests;

use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{

    public function testIsTrue(){
        $user = new User();
        $user->setEmail('true@test.fr')
            ->setPassword('password')
            ->setFirstname('firstname')
            ->setLastname('lastname')
            ->setScreenname('firstname.L');

            $this->assertTrue($user->getEmail() === 'true@test.fr');
            $this->assertTrue($user->getPassword() === 'password');
            $this->assertTrue($user->getFirstname() === 'firstname');
            $this->assertTrue($user->getLastname() === 'lastname');
            $this->assertTrue($user->getScreenname() === 'firstname.L');
        
    }

    public function testIsFalse(){
        $user = new User();
        $user->setEmail('true@test.fr')
            ->setPassword('password')
            ->setFirstname('firstname')
            ->setLastname('lastname')
            ->setScreenname('firstname.L');

        $this->assertFalse($user->getEmail() === 'false@test.fr');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getFirstname() === 'false');
        $this->assertFalse($user->getLastname() === 'false');
        $this->assertFalse($user->getScreenname() === 'false');
    }

    public function testIsEmpty(){
        $user = new User();
        
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getFirstname());
        $this->assertEmpty($user->getLastname());
        $this->assertEmpty($user->getScreenname());
    }
}
