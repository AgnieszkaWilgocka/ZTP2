<?php
/**
 * CategoryService interface
 */
namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CategoryServiceInterface
 */
interface CategoryServiceInterface
{
    /**
     * Paginated list
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save
     *
     * @param Category $category Category
     *
     * @return mixed Mixed
     */
    public function save(Category $category);

    /**
     * Action delete
     *
     * @param Category $category Category
     *
     * @return mixed Mixed
     */
    public function delete(Category $category);

    /**
     * Can be deleted
     *
     * @param Category $category Category
     *
     * @return bool Bool
     */
    public function canBeDeleted(Category $category): bool;

    /**
     * Find One By Id
     *
     * @param int $id Id
     *
     * @return Category|null Category
     */
    public function findOneById(int $id): ?Category;
}
