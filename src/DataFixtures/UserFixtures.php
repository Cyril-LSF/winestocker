<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'firstname' => "john",
                'lastname' => "doe",
                'email' => "john@doe.fr",
                'password' => "johndoe",
                'roles' => ["ROLE_USER"]
            ],
            [
                'firstname' => "jane",
                'lastname' => "doe",
                'email' => "jane@doe.fr",
                'password' => "janedoe",
                'roles' => ["ROLE_MANAGER"],
            ],
        ];

        foreach($users as $item){

            $user = new User();
            $user->setFirstname($item['firstname']);
            $user->setLastname($item['lastname']);
            $user->setEmail($item['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $item['password']));
            $user->setRoles($item['roles']);
            
            $manager->persist($user);

        }

        $manager->flush();
    }
}
