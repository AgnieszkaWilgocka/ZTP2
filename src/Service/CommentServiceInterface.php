<?php
/**
 * CommentService interface.
 */

namespace App\Service;

use App\Entity\Comment;

/**
 * Class CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Action save.
     *
     * @param Comment $comment Comment
     *
     */
    public function save(Comment $comment): void;

    /**
     * Action delete.
     *
     * @param Comment $comment Comment
     *
     */
    public function delete(Comment $comment): void;
}
