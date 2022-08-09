<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 */
#[Route(
    "/comment"
)]
class CommentController extends AbstractController
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Request $request
     * @param Comment $comment
     *
     * @return Response
     *
     * @IsGranted("DELETE", subject="comment")
     */
    #[Route(
        '/{id}/delete',
        name: 'comment_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: "GET|DELETE"
    )]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(FormType::class, $comment,
        [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentRepository->delete($comment);

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),

            ]
        );
    }

//    /**
//     * @param Request $request
//     *
//     * @return Response
//     */
//    #[Route(
//        '/create',
//        name: 'comment_create',
//        methods: 'POST|GET'
//    )]
//    public function create(Request $request): Response
//    {
//        $comment = new Comment();
//        $form = $this->createForm(CommentType::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->commentRepository->save($comment);
//
//            return $this->redirectToRoute('category_index');
//        }
//
//        return $this->render(
//            'comment/create.html.twig',
//            [
//                'form' => $form->createView(),
//            ]
//        );
//    }
}