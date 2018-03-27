<?php

namespace PostBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use PostBundle\Entity\Tag;

class LoadTagData extends Fixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $tag = new Tag();
            $tag->setName($faker->unique()->word);

            $this->setReference('tag_' . $i, $tag);

            $manager->persist($tag);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}