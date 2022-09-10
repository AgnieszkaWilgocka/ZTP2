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
    /**
     * Comment repository
     *
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor
     *
     * @param CommentRepository  $commentRepository Comment repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Action save
     *
     * @param Comment $comment Comment
     *
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Action delete
     *
     * @param Comment $comment Comment
     *
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
