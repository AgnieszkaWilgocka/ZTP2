<?php

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


class CommentService implements CommentServiceInterface
{
    private CommentRepository $commentRepository;

    private PaginatorInterface $paginator;

    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

}