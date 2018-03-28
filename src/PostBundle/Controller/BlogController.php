<?php

namespace PostBundle\Controller;

use PostBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="blog_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     *
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findLatest($page);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postShowAction(Post $post)
    {
        return $this->render('blog/post_show.html.twig',[
            'post' => $post
        ]);
    }

    /**
     * @Route("/search", name="blog_search")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('blog/search.html.twig');
        }

        $query = $request->query->get('search-input', '');
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBySearchQuery($query);

        $results = [];
        foreach ($posts as $post) {
            $results[] = [
                'title' => htmlspecialchars($post->getTitle()),
                'summary' => htmlspecialchars($post->getSummary()),
                'url' => $this->generateUrl('blog_post', ['slug' => $post->getSlug()])
            ];
        }

        return $this->json($results);
    }
}