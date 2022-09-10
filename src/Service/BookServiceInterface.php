<?php
/**
 * BookService interface
 */
namespace App\Service;

use App\Entity\Book;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class BookServiceInterface
 */
interface BookServiceInterface
{
    /**
     * Paginated list
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save
     *
     * @param Book $book
     *
     * @return mixed
     */
    public function save(Book $book);

    /**
     * Action delete
     *
     * @param Book $book
     *
     */
    public function delete(Book $book);

    /**
     * Prepare filters
     *
     * @param array $filters
     *
     * @return array
     */
    public function prepareFilters(array $filters): array;

//    public function canBeDeleted(Book $book): bool;
}
