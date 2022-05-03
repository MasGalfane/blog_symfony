<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('MasGalfane');
        $user->setPassword('$2y$13$c21.FM3RfTa3q5Ct7CxTJ.3wnFWtZqYex6Y3p2yfQzqjJ2i3F1LKa');

        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('$2y$13$oN1QtiM6XbwcmMXhzg4a1.BQtgqt5DYf3AkYIrHNaPwhctCVHtQI2');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
