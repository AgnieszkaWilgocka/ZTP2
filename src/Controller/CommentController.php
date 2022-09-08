<?php
/**
 * Comment controller
 */
namespace App\Controller;

use App\Entity\Comment;
use App\Service\CommentServiceInterface;
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
    /**
     * @var CommentServiceInterface
     */
    private CommentServiceInterface $commentService;

    /**
     * Constructor
     *
     * @param CommentServiceInterface $commentService
     */
    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Function delete comment
     *
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
        $form = $this->createForm(
            FormType::class,
            $comment,
            [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),

            ]
        );
    }
}
