<?php
/**
 * CommentService interface
 */
namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CommentServiceInterface
 */
interface CommentServiceInterface
{
    /**
     * Action save
     *
     * @param Comment $comment
     */
    public function save(Comment $comment): void;

    /**
     * Action delete
     *
     * @param Comment $comment
     */
    public function delete(Comment $comment): void;
}
