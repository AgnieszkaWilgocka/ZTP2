<?php

namespace App\Service;

use App\Entity\Book;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface BookServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;
    public function save(Book $book);
    public function delete(Book $book);
    public function canBeDeleted(Book $book): bool;
    public function prepareFilters(array $filters): array;
}
