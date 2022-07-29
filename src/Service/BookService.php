<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * class BookService
 */
class BookService implements BookServiceInterface
{
    /**
     * Book Repository
     *
     * @var BookRepository
     */
    private BookRepository $bookRepository;

    /**
     * Paginator
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * CategoryRepository
     *
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * Constructor
     *
     * @param BookRepository $bookRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator, CategoryRepository $categoryRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->categoryRepository = $categoryRepository;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->queryAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Book $book)
    {
        $this->bookRepository->save($book);
    }

    public function delete(Book $book)
    {
        $this->bookRepository->delete($book);
    }

    public function canBeDeleted(Book $book): bool
    {
        try {
            $result = $this->categoryRepository->countByBook($book);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}