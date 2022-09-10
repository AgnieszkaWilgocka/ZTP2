<?php
/**
 * Book service
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
 * Class BookService
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
     * Tag Service
     *
     * @var TagService
     */
    private TagService $tagService;

    /**
     * Category Service
     *
     * @var CategoryService
     */
    private CategoryService $categoryService;


    /**
     * Constructor
     *
     * @param BookRepository     $bookRepository
     * @param PaginatorInterface $paginator
     * @param CategoryRepository $categoryRepository
     * @param TagService         $tagService
     * @param CategoryService    $categoryService
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
     * Paginated list
     *
     * @param int   $page
     * @param array $filters
     *
     * @return PaginationInterface
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
     * Action save
     *
     * @param Book $book
     *
     * @return void
     */
    public function save(Book $book)
    {
        $this->bookRepository->save($book);
    }

    /**
     * Action delete
     *
     * @param Book $book
     *
     * @return void
     */
    public function delete(Book $book)
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Prepare filters function
     *
     * @param array $filters
     *
     * @return \App\Entity\Tag[]
     *
     * @psalm-return array{tag?: \App\Entity\Tag}
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
