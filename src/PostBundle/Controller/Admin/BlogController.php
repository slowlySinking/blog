<?php

namespace PostBundle\Controller\Admin;

use PostBundle\Entity\Post;
use PostBundle\Form\PostType;
use PostBundle\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/post")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="admin_post_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="admin_post_create")
     *
     * @param Request $request
     * @param Slugger $slugger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createPostAction(Request $request, Slugger $slugger)
    {
        $em = $this->getDoctrine()->getManager();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setSlug($slugger->sluggify($post->getTitle()));

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'post.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_post_create');
            }

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/blog/create.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_post_show")
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Post $post)
    {
        $this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');

        return $this->render('admin/blog/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_post_edit")
     *
     * @param Request $request
     * @param Post $post
     * @param Slugger $slugger
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post, Slugger $slugger)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugger->sluggify($post->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
        }

        return $this->render('admin/blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_post_delete")
     * @Security("is_granted('delete', post)")
     *
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Post $post)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_post_index');
        }

        $post->getTags()->clear();

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'post.deleted_successfully');

        return $this->redirectToRoute('admin_post_index');
    }
}