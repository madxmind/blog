<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostType;
use App\Handler\CommentHandler;
use App\Handler\PostHandler;
use App\Service\UploaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(1, 5000),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/lire-{id}", name="blog_read")
     * @param  Post $post
     * @return Response
     */
    public function read(Post $post, Request $request, CommentHandler $commentHandler): Response
    {
        $comment = new Comment;
        $comment->setPost($post);

        if ($commentHandler->handle($request, $comment, [
            'validation_groups' => $this->isGranted('ROLE_USER') ? 'Default' : ['Default', 'anonymous']
        ])) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/read.html.twig', [
            'post' => $post,
            'form' => $commentHandler->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/publier-article", name="blog_create")
     * @param  Request $request
     * @param  UploaderInterface $uploader
     * @param  PostHandler $postHandler
     * @return Response
     */
    public function create(Request $request, PostHandler $postHandler): Response
    {
        $post = new Post;
        $post->setUser($this->getUser());

        if ($postHandler->handle($request, $post, [
            'validation_groups' => ['Default', 'create']
        ])) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'form' => $postHandler->createView(),
        ]);
    }

    /**
     * @Route("/modifer-article/{id}", name="blog_update")
     * @param  Post $post
     * @param  Request $request
     * @param  UploaderInterface $uploader
     * @param  PostHandler $postHandler
     * @return Response
     */
    public function update(Post $post, Request $request, PostHandler $postHandler): Response
    {
        if ($postHandler->handle($request, $post)) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/update.html.twig', [
            'form' => $postHandler->createView(),
        ]);
    }
}
