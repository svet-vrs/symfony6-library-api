<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setPassword('$2y$13$ZL5R.HF5gfj6FXxdmZM15OiTbOIx73ueVDpd7KjUHskN1CplkxZA.');
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}