<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class BookService implements BookServiceInterface
{
    /**
     * Book Repository.
     */
    private BookRepository $bookRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * CategoryRepository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Tag Service.
     */
    private TagService $tagService;

    /**
     * Category Service.
     */
    private CategoryService $categoryService;

    /**
     * Constructor.
     *
     * @param BookRepository     $bookRepository     Book repository
     * @param PaginatorInterface $paginator          Paginator
     * @param CategoryRepository $categoryRepository Category repository
     * @param TagService         $tagService         Tag service
     * @param CategoryService    $categoryService    Category service
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator, CategoryRepository $categoryRepository, TagService $tagService, CategoryService $categoryService)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->categoryRepository = $categoryRepository;
        $this->tagService = $tagService;
        $this->categoryService = $categoryService;
    }

    /**
     * Paginated list.
     *
     * @param int   $page    Page
     * @param array $filters Filters
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bookRepository->queryAll($filters),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Action save.
     *
     * @param Book $book Book
     */
    public function save(Book $book)
    {
        $this->bookRepository->save($book);
    }

    /**
     * Action delete.
     *
     * @param Book $book Book
     */
    public function delete(Book $book)
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Prepare filters function.
     *
     * @param array $filters Filters
     *
     * @return \App\Entity\Tag[] Tag
     */
    public function prepareFilters(array $filters): array
    {
        $resultFilters = [];

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        return $resultFilters;
    }

//    public function canBeDeleted(Book $book): bool
//    {
//        try {
//            $result = $this->tagRepository->countByBook($book);
//
//            return !($result > 0);
//        } catch (NoResultException|NonUniqueResultException) {
//            return false;
//        }
//    }
}
