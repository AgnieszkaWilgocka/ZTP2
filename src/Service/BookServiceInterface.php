<?php
/**
 * BookService interface.
 */

namespace App\Service;

use App\Entity\Book;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class BookServiceInterface.
 */
interface BookServiceInterface
{
    /**
     * Paginated list.
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save.
     *
     * @param Book $book Book
     *
     * @return mixed mixed
     */
    public function save(Book $book);

    /**
     * Action delete.
     *
     * @param Book $book Book
     */
    public function delete(Book $book);

    /**
     * Prepare filters.
     *
     * @param array $filters Filters
     *
     * @return array Filters
     */
    public function prepareFilters(array $filters): array;

//    public function canBeDeleted(Book $book): bool;
}
