<?php

namespace PostBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use PostBundle\Entity\Post;
use PostBundle\Utils\Slugger;

class LoadPostData extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var Slugger
     */
    private $slugger;

    /**
     * LoadPostData constructor.
     * @param Slugger $slugger
     */
    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(3));
            $post->setSlug($this->slugger->sluggify($post->getTitle()));
            $post->setContent($faker->text());
            $post->setSummary($faker->sentence(15));

            $post->addTag($this->getReference('tag_' . $i));
            $post->addTag($this->getReference('tag_' . ($i + 10)));

            $post->setUser($this->getReference('user_' . $i));

            $this->setReference('post_' . $i, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}