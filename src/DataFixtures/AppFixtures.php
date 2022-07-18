<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;
    private Generator $generator;

    public function load(ObjectManager $manager): void
    {
        //création de 10 utilisateurs
        $this->manager = $manager;
        $this->addUser();
    }

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->generator = Factory::create("fr_FR");
    }

    public function addUser()
    {
        for($i =0 ; $i <= 10 ; $i++){
            $user = new User();
            $user
                ->setName($this->generator->lastName)
                ->setFirstname($this->generator->firstName)
                ->setEmail($this->generator->email)
                ->setPhone($this->generator->phoneNumber)
                ->setRoles(["ROLE_USER"])
                ->setIsActive(1);

            $password = $this->hasher->hashPassword($user, "12345678");
            $user->setPassword($password);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }

}
