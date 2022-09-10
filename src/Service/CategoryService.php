<?php
/**
 * Category service
 */
namespace App\Service;

use App\Entity\Category;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService
 *
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository
     *
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * Paginator
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * Book repository
     *
     * @var BookRepository
     */
    private BookRepository $bookRepository;

    /**
     * Constructor
     *
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param BookRepository     $bookRepository
     */
    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator, BookRepository $bookRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Paginated list
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Action save
     *
     * @param Category $category
     *
     */
    public function save(Category $category)
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Action delete
     *
     * @param Category $category
     *
     */
    public function delete(Category $category)
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Action can be deleted
     *
     * @param Category $category
     *
     * @return bool
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->bookRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * Find one by id
     *
     * @param int $id
     *
     * @return Category|null
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }
}
