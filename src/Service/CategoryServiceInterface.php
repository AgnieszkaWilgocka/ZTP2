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
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Action save
     *
     * @param Category $category
     *
     * @return mixed
     */
    public function save(Category $category);

    /**
     * Action delete
     *
     * @param Category $category
     *
     * @return mixed
     */
    public function delete(Category $category);

    /**
     * Can be deleted
     *
     * @param Category $category
     *
     * @return bool
     */
    public function canBeDeleted(Category $category): bool;
}
