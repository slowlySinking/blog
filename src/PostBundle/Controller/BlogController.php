<?php

namespace PostBundle\Controller;

use PostBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/index", name="blog_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findLatest();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}