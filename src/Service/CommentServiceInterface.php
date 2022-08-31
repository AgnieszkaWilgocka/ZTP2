<?php
namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * CommentServiceInterface
 */
interface CommentServiceInterface
{
    public function save(Comment $comment): void;
    public function delete(Comment $comment): void;
}