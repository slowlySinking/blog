<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use UserBundle\Entity\User;

class LoadUserData extends Fixture implements OrderedFixtureInterface
{
    // Hash for '12345' password
    const HASHED_PASSWORD = '$2y$13$W9WVbvFG3kvKWIqd.W6CDuyS5anFlS6OWiamUkobVHD4zYov8CAmm';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstName($faker->title() . $faker->firstName());
            $user->setLastName($faker->lastName);
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setPassword(self::HASHED_PASSWORD);
            $user->setRoles(['ROLE_USER']);

            $this->setReference('user_' . $i, $user);

            $manager->persist($user);
        }

        $admin = new User();
        $admin->setFirstName($faker->title() . $faker->firstName());
        $admin->setLastName($faker->lastName);
        $admin->setUsername('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword(self::HASHED_PASSWORD);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}