<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Post;
use App\Form\CommentsType;
use App\Form\PostType;
use App\Repository\CommentsRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('post_index');
        }
        $post = new Post();
        $post->setDate(new \DateTime());
        $post->setUserNew($this->getUser());
        $post->setNumOfViews(0);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET","POST"})
     */
    public function show(Post $post,Request $request, CommentsRepository $commentsRepository): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('post_index');
        }
        $comment = new Comments();
        $comment->setUserNew($this->getUser());
        $comment->setPostNew($post);
        $commentForm = $this->createForm(CommentsType::class,$comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','Комментарий добавлен!');
            return $this->redirect($request->getUri());
        }

        $post->setNumOfViews($post->getNumOfViews()+1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $commentsRepository->findBy(array('post_new' => $post->getId()), array('id' => 'DESC')),
            'formC' => $commentForm->createView()
        ]);
    }
}
