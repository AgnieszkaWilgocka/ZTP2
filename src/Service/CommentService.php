<?php
/**
 * Comment service
 */
namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService
 *
 */
class CommentService implements CommentServiceInterface
{
    private CommentRepository $commentRepository;

    private PaginatorInterface $paginator;

    /**
     * Constructor
     *
     * @param CommentRepository  $commentRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Action save
     *
     * @param Comment $comment
     *
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Action delete
     *
     * @param Comment $comment
     *
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
