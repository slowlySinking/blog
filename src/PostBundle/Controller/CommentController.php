<?php

namespace PostBundle\Controller;

use PostBundle\Entity\Comment;
use PostBundle\Entity\Post;
use PostBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentFormAction(Post $post)
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create/{postSlug}", name="comment_create")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * @param Request $request
     * @param Post $post
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createCommentAction(Request $request, Post $post, EventDispatcherInterface $eventDispatcher)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $comment->setPost($post);
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('blog_post', [
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}