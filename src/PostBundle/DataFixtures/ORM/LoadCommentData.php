<?php

namespace PostBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use PostBundle\Entity\Comment;

class LoadCommentData extends Fixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 2; $i++) {
            for ($k = 0; $k < 5; $k++) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph());
                $comment->setPost($this->getReference('post_' . $i));
                $comment->setUser($this->getReference('user_' . $i));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}